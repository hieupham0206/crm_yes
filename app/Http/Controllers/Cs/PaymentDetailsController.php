<?php

namespace App\Http\Controllers\Cs;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Contract;
use App\Models\PaymentCost;
use App\Models\PaymentDetail;
use App\Tables\Cs\PaymentDetailTable;
use App\Tables\TableFacade;
use Illuminate\Http\Request;

class PaymentDetailsController extends Controller
{
    /**
     * Tên dùng để phân quyền
     * @var string
     */
    protected $name = 'paymentDetail';

    /**
     * Hiển thị trang danh sách PaymentDetail.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('cs.payment_details.index')->with('paymentDetail', new PaymentDetail);
    }

    /**
     * Lấy danh sách PaymentDetail cho trang table ở trang index
     * @return string
     */
    public function table()
    {
        return (new TableFacade(new PaymentDetailTable()))->getDataTable();
    }

    /**
     * Trang tạo mới PaymentDetail.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('cs.payment_details.create', [
            'paymentDetail' => new PaymentDetail,
            'action'        => route('payment_details.store'),
        ]);
    }

    /**
     * Lưu PaymentDetail mới.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'total_paid_deal' => 'required',
        ]);
        $requestData   = $request->all();
        $paymentDetail = PaymentDetail::create($requestData);

        if ($request->wantsJson()) {
            return $this->asJson([
                'message' => __('Data created successfully'),
            ]);
        }

        return redirect(route('payment_details.show', $paymentDetail))->with('message', __('Data created successfully'));
    }

    /**
     * Trang xem chi tiết PaymentDetail.
     *
     * @param  PaymentDetail $paymentDetail
     *
     * @return \Illuminate\View\View
     */
    public function show(PaymentDetail $paymentDetail)
    {
        return view('cs.payment_details.show', compact('paymentDetail'));
    }

    /**
     * Trang cập nhật PaymentDetail.
     *
     * @param  PaymentDetail $paymentDetail
     *
     * @return \Illuminate\View\View
     */
    public function edit(PaymentDetail $paymentDetail)
    {
        return view('cs.payment_details.edit', [
            'paymentDetail' => $paymentDetail,
            'paymentCost'   => new PaymentCost(),
            'method'        => 'put',
            'action'        => route('payment_details.update', $paymentDetail),
        ]);
    }

    /**
     * Cập nhật PaymentDetail tương ứng.
     *
     * @param \Illuminate\Http\Request $request
     * @param  PaymentDetail $paymentDetail
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, PaymentDetail $paymentDetail)
    {
        $this->validate($request, [
            'total_paid_real' => 'required',
        ]);

        $requestData                    = $request->all();
        $requestData['total_paid_real'] = str_replace(',', '', $requestData['total_paid_real']);
        $requestData['pay_date_real']   = date('Y-m-d', strtotime($requestData['pay_date_real']));

        //note: nếu số tiền trả thực tế chênh lệch với tiền hẹn trả thì dồn vào lần cuối
        $diffAmount = $requestData['total_paid_real'] - $paymentDetail->total_paid_deal;
        if ($diffAmount != 0) {
            $lastPay = PaymentDetail::whereContractId($paymentDetail->contract_id)->where('total_paid_real', 0)->whereNull('pay_date_real')->orderBy('pay_date', 'desc')->first();
            if ($lastPay) {
                $leftAmount = $lastPay->total_paid_deal - $diffAmount;
                $lastPay->update(['total_paid_deal' => $leftAmount]);
            }
        }
        $paymentDetail->update($requestData);

        //note: nếu thanh toán hop dong done thi tao commisison
        /** @var Contract $contract */
        $contract                = $paymentDetail->contract;
        $paymentDetails          = $contract->payment_details()->where('total_paid_real', 0)->whereNull('pay_date_real')->get();
        $contract->total_payment += $requestData['total_paid_real'];

        if ($paymentDetails->count() === 0) {
            $contract->markContractDone();

            $eventData   = $contract->event_data;
            $appointment = $eventData->appointment;
            if ($appointment) {
                $userId = $appointment->ambassador ?? $appointment->user_id;
            }

            Commission::create([
                'contract_id' => $contract->id,
                'user_id'     => $userId ?? null,
                'net_total'   => $contract->net_amount,
            ]);
        }

        if ($request->wantsJson()) {
            return $this->asJson([
                'message' => __('Data edited successfully'),
            ]);
        }

//        return redirect(route('payment_details.show', $paymentDetail))->with('message', __('Data edited successfully'));
        return redirect(route('payment_details.index'))->with('message', __('Data edited successfully'));
    }

    /**
     * Xóa PaymentDetail.
     *
     * @param PaymentDetail $paymentDetail
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(PaymentDetail $paymentDetail)
    {
        try {
            $paymentDetail->delete();
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
     * Xóa nhiều PaymentDetail.
     *
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag
     * @throws \Exception
     */
    public function destroys()
    {
        try {
            $ids = \request()->get('ids');
            PaymentDetail::destroy($ids);
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
     * Lấy danh sách PaymentDetail theo dạng json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentDetails()
    {
        $query          = request()->get('query', '');
        $page           = request()->get('page', 1);
        $excludeIds     = request()->get('excludeIds', []);
        $offset         = ($page - 1) * 10;
        $paymentDetails = PaymentDetail::query()->select(['id', 'name']);

        $paymentDetails->andFilterWhere([
            ['name', 'like', $query],
            ['id', '!=', $excludeIds],
        ]);

        $totalCount     = $paymentDetails->count();
        $paymentDetails = $paymentDetails->offset($offset)->limit(10)->get();

        return $this->asJson([
            'total_count' => $totalCount,
            'items'       => $paymentDetails->toArray(),
        ]);
    }
}