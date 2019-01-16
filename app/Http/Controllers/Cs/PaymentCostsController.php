<?php

namespace App\Http\Controllers\Cs;

use App\Http\Controllers\Controller;
use App\Models\PaymentCost;
use App\Tables\Cs\PaymentCostTable;
use App\Tables\TableFacade;
use Illuminate\Http\Request;

class PaymentCostsController extends Controller
{
    /**
     * Tên dùng để phân quyền
     * @var string
     */
    protected $name = 'paymentCost';

    /**
     * Hiển thị trang danh sách PaymentCost.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('cs.payment_costs.index')->with('paymentCost', new PaymentCost);
    }

    /**
     * Lấy danh sách PaymentCost cho trang table ở trang index
     * @return string
     */
    public function table()
    {
        return (new TableFacade(new PaymentCostTable()))->getDataTable();
    }

    /**
     * Trang tạo mới PaymentCost.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('cs.payment_costs.create', [
            'paymentCost' => new PaymentCost,
            'action'      => route('payment_costs.store'),
        ]);
    }

    /**
     * Lưu PaymentCost mới.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'bank_name' => 'required'
        ]);
        $requestData = $request->all();
        $paymentCost = PaymentCost::create($requestData);

        if ($request->wantsJson()) {
            return $this->asJson([
                'message' => __('Data created successfully'),
            ]);
        }

        return redirect(route('payment_costs.index'))->with('message', __('Data created successfully'));
    }

    /**
     * Trang xem chi tiết PaymentCost.
     *
     * @param  PaymentCost $paymentCost
     *
     * @return \Illuminate\View\View
     */
    public function show(PaymentCost $paymentCost)
    {
        return view('cs.payment_costs.show', compact('paymentCost'));
    }

    /**
     * Trang cập nhật PaymentCost.
     *
     * @param  PaymentCost $paymentCost
     *
     * @return \Illuminate\View\View
     */
    public function edit(PaymentCost $paymentCost)
    {
        return view('cs.payment_costs.edit', [
            'paymentCost' => $paymentCost,
            'method'      => 'put',
            'action'      => route('payment_costs.update', $paymentCost),
        ]);
    }

    /**
     * Cập nhật PaymentCost tương ứng.
     *
     * @param \Illuminate\Http\Request $request
     * @param  PaymentCost $paymentCost
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, PaymentCost $paymentCost)
    {
        $this->validate($request, [
            'bank_name' => 'required'
        ]);
        $requestData = $request->all();
        $paymentCost->update($requestData);

        if ($request->wantsJson()) {
            return $this->asJson([
                'message' => __('Data edited successfully'),
            ]);
        }

        return redirect(route('payment_costs.index'))->with('message', __('Data edited successfully'));
    }

    /**
     * Xóa PaymentCost.
     *
     * @param PaymentCost $paymentCost
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(PaymentCost $paymentCost)
    {
        try {
            $paymentCost->delete();
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
     * Xóa nhiều PaymentCost.
     *
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag
     * @throws \Exception
     */
    public function destroys()
    {
        try {
            $ids = \request()->get('ids');
            PaymentCost::destroy($ids);
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
     * Lấy danh sách PaymentCost theo dạng json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function paymentCosts()
    {
        $query        = request()->get('query', '');
        $page         = request()->get('page', 1);
        $excludeIds   = request()->get('excludeIds', []);
        $offset       = ($page - 1) * 10;
        $paymentCosts = PaymentCost::query()->select(['id', 'name']);

        $paymentCosts->andFilterWhere([
            ['name', 'like', $query],
            ['id', '!=', $excludeIds],
        ]);

        $totalCount   = $paymentCosts->count();
        $paymentCosts = $paymentCosts->offset($offset)->limit(10)->get();

        return $this->asJson([
            'total_count' => $totalCount,
            'items'       => $paymentCosts->toArray(),
        ]);
    }

    public function getBank()
    {
        $method = request()->get('method');

        if ($method) {
            $banks = PaymentCost::where('payment_method', $method)->get();

            return $this->asJson([
                'items' => $banks,
            ]);
        }

        return $this->asJson([
            'items' => [],
        ]);
    }
}