<?php

namespace App\Http\Controllers\Cs;

use App\Enums\LeadState;
use App\Http\Controllers\Controller;
use App\Mail\WelcomeLetter;
use App\Models\Contract;
use App\Models\EventData;
use App\Models\Lead;
use App\Models\Member;
use App\Models\PaymentCost;
use App\Models\PaymentDetail;
use App\Tables\Cs\ContractTable;
use App\Tables\TableFacade;
use App\TechAPI\FptSms;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ContractsController extends Controller
{
    /**
     * Tên dùng để phân quyền
     * @var string
     */
    protected $name = 'contract';

    /**
     * Hiển thị trang danh sách Contract.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        return view('cs.contracts.index')->with('contract', new Contract)->with('eventData', new EventData);
    }

    /**
     * Lấy danh sách Contract cho trang table ở trang index
     * @return string
     */
    public function table()
    {
        return (new TableFacade(new ContractTable()))->getDataTable();
    }

    /**
     * Trang tạo mới Contract.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $eventDataId = request()->get('eventDataId');
        if ( ! $eventDataId) {
            abort(404);
        }

        $eventData   = EventData::find($eventDataId);
        $appointment = $eventData->appointment;
        $lead        = $appointment->lead;

        $contract = new Contract;
//        $contract->fill([
//            'contract_no'    => time(),
////            'contract_no'    => '1548129013',
//            'amount'         => '1000000',
//            'signed_date'    => '22-01-2019',
//            'start_date'     => '22-01-2019',
//            'membership'     => 1,
//            'room_type'      => 1,
//            'limit'          => 1,
//            'end_time'       => 1,
//            'num_of_payment' => 2,
//            'pay_date'       => '22-01-2019',
//        ]);

        return view('cs.contracts.create', [
            'contract'    => $contract,
            'eventData'   => $eventData,
            'lead'        => $lead,
            'member'      => new Member,
            'paymentCost' => new PaymentCost,
            'appointment' => $appointment,
            'action'      => route('contracts.store'),
        ]);
    }

    /**
     * Lưu Contract mới.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $requestData = $request->all();
        $this->validate($request, [
            'contract_no' => 'required',
        ]);

        \DB::beginTransaction();
        try {
            //note: check kiem tra sdt hoac email da lam member chua
            $identityHusband = $requestData['identity'];
            $identityWife    = $requestData['spouse_identity'];

            if ( ! $member = Member::isMember($identityHusband, $identityWife)) {
                $member = Member::create($requestData);
            }

            //note: hạn mức trọn đời
            if ($request->has('lifetime')) {
                $requestData['end_time'] = 0;
            }
            $requestData['contract_no'] = Contract::createContractNo($requestData['contract_no'], $requestData['city']);

            if (Contract::checkContractNoExists($requestData['contract_no'])) {
                $validator = \Validator::make([], []); // Empty data and rules fields
                $validator->errors()->add('contract_no', 'Số hợp đồng đã tồn tại');

                throw new ValidationException($validator);
            }

            $feeCosts = PaymentCost::where('payment_method', 5)->get(['cost'])->sum('cost');

            $requestData['member_id']  = $member->id;
            $requestData['amount']     = str_replace(',', '', $requestData['amount']);
            $requestData['net_amount'] = $requestData['amount'] - $feeCosts;
            $requestData['year_cost']  = str_replace(',', '', $requestData['year_cost']);
            ++$requestData['num_of_payment'];
            $contract = Contract::create($requestData);

            //note: gửi sms/email welcome letter
            $message = (new WelcomeLetter([]))->onConnection('database')->onQueue('notification');
            \Mail::to($member->email)->queue($message);

            $fptSms = new FptSms();
            $fptSms->sendWelcome($contract->contract_no,  $member->phone);

            //note: cập nhật state của lead thành member
            $leadId = $requestData['lead_id'];
            $lead   = Lead::find($leadId);
            if ($lead) {
                $lead->update(['state' => LeadState::MEMBER]);
            }

            //note: tạo payment_detail

            //1. PaymentDetail dau tien
            $totalPaidDeal = $requestData['total_paid_deal'];
            $payDate       = $requestData['pay_date'];
            $bankName      = $requestData['bank_name'];
            $paymentMethod = $requestData['payment_method'];

            $paymentCost = PaymentCost::where([
                'bank_name'      => $bankName,
                'payment_method' => $paymentMethod,
            ])->first();
            $payTime     = 1;
            if ($totalPaidDeal) {
                PaymentDetail::create([
                    'pay_date'        => $payDate,
                    'total_paid_deal' => str_replace(',', '', $totalPaidDeal),
                    'pay_date_real'   => $payDate,
                    'total_paid_real' => str_replace(',', '', $totalPaidDeal),
                    'contract_id'     => $contract->id,
                    'payment_cost_id' => optional($paymentCost)->id,

                    'bank_name'  => $bankName,
                    'bank_no'    => $requestData['bank_no'],
                    'note'       => $requestData['note'],
                    'pay_time'   => $payTime,
                    'created_at' => now()->toDateTimeString(),
                ]);
            }

            if ($request->has('PaymentDetail')) {
                $paymentDates   = collect($requestData['PaymentDetail']['pay_date'])->flatten()->toArray();
                $totalPaidDeals = collect($requestData['PaymentDetail']['total_paid_deal'])->flatten()->toArray();

                $paymentDetailDatas = [];

                foreach ($paymentDates as $key => $paymentDate) {
                    $paymentDetailDatas[] = [
                        'pay_date'        => date('Y-m-d', strtotime($paymentDate)),
                        'total_paid_deal' => str_replace(',', '', $totalPaidDeals[$key]),
                        'contract_id'     => $contract->id,
                        'payment_cost_id' => optional($paymentCost)->id,

                        'bank_name'  => $bankName,
                        'bank_no'    => $requestData['bank_no'],
                        'note'       => $requestData['note'],
                        'pay_time'   => ++$payTime,
                        'created_at' => now()->toDateTimeString(),
                    ];
                }

                PaymentDetail::insert($paymentDetailDatas);
            }

            \DB::commit();
            if ($request->wantsJson()) {
                return $this->asJson([
                    'message' => __('Data created successfully'),
                ]);
            }

            return redirect(route('contracts.index'))->with('message', __('Data created successfully'));

        } catch (Exception $e) {
            \DB::rollBack();

            return redirect(route('contracts.index'))->with('message', __('Data created unsuccessfully'));
        }
    }

    /**
     * Trang xem chi tiết Contract.
     *
     * @param  Contract $contract
     *
     * @return \Illuminate\View\View
     */
    public function show(Contract $contract)
    {
        $paymentDetails     = $contract->payment_details;
        $firstPaymentDetail = $paymentDetails->first();

        return view('cs.contracts.show', [
            'contract'           => $contract,
            'firstPaymentDetail' => $firstPaymentDetail,
            'paymentDetails'     => $paymentDetails,
            'lead'               => new Lead(),
            'member'             => $contract->member,
            'paymentCost'        => new PaymentCost,
        ]);
    }

    /**
     * Trang cập nhật Contract.
     *
     * @param  Contract $contract
     *
     * @return \Illuminate\View\View
     */
    public function edit(Contract $contract)
    {
        $paymentDetails     = $contract->payment_details;
        $firstPaymentDetail = $paymentDetails->first();

        return view('cs.contracts.edit', [
            'contract'           => $contract,
            'paymentDetails'     => $paymentDetails,
            'firstPaymentDetail' => $firstPaymentDetail,
            'lead'               => new Lead(),
            'member'             => $contract->member,
            'paymentCost'        => new PaymentCost,
            'method'             => 'put',
            'action'             => route('contracts.update', $contract),
        ]);
    }

    /**
     * Cập nhật Contract tương ứng.
     *
     * @param \Illuminate\Http\Request $request
     * @param  Contract $contract
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Contract $contract)
    {
        $this->validate($request, [
            'contract_no' => 'required',
        ]);
        $requestData = $request->all();

        $requestData['amount']    = str_replace(',', '', $requestData['amount']);
        $requestData['year_cost'] = str_replace(',', '', $requestData['year_cost']);

        //note: check neu gia tri hop dong thay doi thi cap nhat vao lan hen trả cuoi cùng
        $amountChange = $contract->amount - $requestData['amount'];
        if ($amountChange != 0) {
            /** @var PaymentDetail $lastPaymentDetail */
            $lastPaymentDetail = $contract->payment_details()->orderBy('pay_date', 'desc')->first();

            if ($lastPaymentDetail) {
                $totalPaid = $lastPaymentDetail->total_paid_deal - $amountChange;
                $lastPaymentDetail->update([
                    'total_paid_deal' => $totalPaid,
                ]);
            }
        }

        $contract->update($requestData);

        //note: update member
        $member = $contract->member;
        $member->fill($requestData);
        if ($member->isDirty()) {
            $member->update();
        }

        if ($request->wantsJson()) {
            return $this->asJson([
                'message' => __('Data edited successfully'),
            ]);
        }

        return redirect(route('contracts.show', $contract))->with('message', __('Data edited successfully'));
    }

    /**
     * Xóa Contract.
     *
     * @param Contract $contract
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Contract $contract)
    {
        try {
            $contract->delete();
        } catch (\Exception $e) {
            return $this->asJson([
                'message' => "Error: {$e->getMessage()}",
            ], $e->getCode());
        }

        return $this->asJson([
            'message' => __('Data deleted successfully'),
        ]);
    }

    /**
     * Xóa nhiều Contract.
     *
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag
     * @throws \Exception
     */
    public function destroys()
    {
        try {
            $ids = \request()->get('ids');
            Contract::destroy($ids);
        } catch (\Exception $e) {
            return $this->asJson([
                'message' => "Error: {$e->getMessage()}",
            ], $e->getCode());
        }

        return $this->asJson([
            'message' => __('Data deleted successfully'),
        ]);
    }

    /**
     * Lấy danh sách Contract theo dạng json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function contracts()
    {
        $query      = request()->get('query', '');
        $page       = request()->get('page', 1);
        $excludeIds = request()->get('excludeIds', []);
        $offset     = ($page - 1) * 10;
        $contracts  = Contract::query()->select(['id', 'name']);

        $contracts->andFilterWhere([
            ['name', 'like', $query],
            ['id', '!=', $excludeIds],
        ]);

        $totalCount = $contracts->count();
        $contracts  = $contracts->offset($offset)->limit(10)->get();

        return $this->asJson([
            'total_count' => $totalCount,
            'items'       => $contracts->toArray(),
        ]);
    }

    /**
     * @param Request $request
     * @param Contract $contract
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeState(Request $request, Contract $contract)
    {
        $state = $request->post('state');

        try {
            if ($state !== null && $contract->update(['state' => $state])) {
                return response()->json([
                    'message' => __('Data edited successfully'),
                ]);
            }

            return response()->json([
                'message' => __('Data edited unsuccessfully'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => "Error: {$e->getMessage()}",
            ], $e->getCode());
        }
    }
}