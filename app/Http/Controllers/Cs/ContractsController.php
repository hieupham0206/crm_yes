<?php

namespace App\Http\Controllers\Cs;

use App\Enums\LeadState;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\EventData;
use App\Models\Lead;
use App\Models\Member;
use App\Models\PaymentCost;
use App\Models\PaymentDetail;
use App\Tables\Cs\ContractTable;
use App\Tables\TableFacade;
use Illuminate\Http\Request;

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

        return view('cs.contracts.create', [
            'contract'    => new Contract,
            'eventData'   => EventData::find($eventDataId),
            'lead'        => new Lead,
            'paymentCost' => new PaymentCost,
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
        $this->validate($request, [
            'contract_no' => 'required',
        ]);
        $requestData = $request->all();

        //note: check kiem tra sdt hoac email da lam member chua
        $identityHusband = $requestData['identity_husband'];
        $identityWife    = $requestData['identity_wife'];

        if ( ! $member = Member::isMember($identityHusband, $identityWife)) {
            $member = Member::create($requestData);
        } else {
            $validator = \Validator::make([], []); // Empty data and rules fields
            $validator->errors()->add('identity_husband', 'Thông tin member đã tồn tại');

            throw new ValidationException($validator);
        }
        $requestData['member_id'] = $member->id;
        $contract    = Contract::create($requestData);

        //note: cập nhật state của lead thành member
        $lead = $member->lead;
        $lead->update(['state' => LeadState::MEMBER]);

        //note: tạo payment_detail
        if ($request->has('PaymentDetail')) {
            $paymentDates   = collect($requestData['PaymentDetail']['payment_date'])->flatten()->toArray();
            $totalPaidDeals = collect($requestData['PaymentDetail']['total_paid_deal'])->flatten()->toArray();

            $paymentDetailDatas = [];

            foreach ($paymentDates as $key => $paymentDate) {
                $paymentDetailDatas[] = [
                    'pay_date'        => $paymentDate,
                    'total_paid_deal' => $totalPaidDeals[$key],
                    'contract_id'     => $contract->id,
                ];
            }

            PaymentDetail::insert($paymentDetailDatas);
        }

        if ($request->wantsJson()) {
            return $this->asJson([
                'message' => __('Data created successfully'),
            ]);
        }

        return redirect(route('contracts.show', $contract))->with('message', __('Data created successfully'));
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
        return view('cs.contracts.show', compact('contract'));
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
        return view('cs.contracts.edit', [
            'contract' => $contract,
            'method'   => 'put',
            'action'   => route('contracts.update', $contract),
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
        $contract->update($requestData);

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
}