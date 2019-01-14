<?php

namespace App\Http\Controllers\Business;

use App\Exports\CallbackExport;
use App\Http\Controllers\Controller;
use App\Models\Callback;
use App\Tables\TableFacade;
use App\Tables\Business\CallbackTable;
use Illuminate\Http\Request;

class CallbacksController extends Controller
{
     /**
      * Tên dùng để phân quyền
      * @var string
      */
	 protected $name = 'callback';

    /**
     * Hiển thị trang danh sách Callback.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view( 'business.callbacks.index' )->with('callback', new Callback);
    }

    /**
     * Lấy danh sách Callback cho trang table ở trang index
     * @return string
     */
    public function table() {
    	return ( new TableFacade( new CallbackTable() ) )->getDataTable();
    }

    /**
     * Trang tạo mới Callback.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('business.callbacks.create', [
            'callback' => new Callback,
            'action' => route('callbacks.store')
        ]);
    }

    /**
     * Lưu Callback mới.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request, [
			'name' => 'required'
		]);
        $requestData = $request->all();
        $callback = Callback::create($requestData);

        if ($request->wantsJson()) {
            return $this->asJson([
                'message' => __('Data created successfully')
            ]);
        }

        return redirect(route('callbacks.show', $callback))->with('message', __( 'Data created successfully' ));
    }

    /**
     * Trang xem chi tiết Callback.
     *
     * @param  Callback $callback
     * @return \Illuminate\View\View
     */
    public function show(Callback $callback)
    {
        return view('business.callbacks.show', compact('callback'));
    }

    /**
     * Trang cập nhật Callback.
     *
     * @param  Callback $callback
     * @return \Illuminate\View\View
     */
    public function edit(Callback $callback)
    {
        return view('business.callbacks.edit', [
            'callback' => $callback,
            'method' => 'put',
            'action' => route('callbacks.update', $callback)
        ]);
    }

    /**
     * Cập nhật Callback tương ứng.
     *
     * @param \Illuminate\Http\Request $request
     * @param  Callback $callback
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, Callback $callback)
    {
        $this->validate($request, [
			'name' => 'required'
		]);
        $requestData = $request->all();
        $callback->update($requestData);

        if ($request->wantsJson()) {
            return $this->asJson([
                'message' => __('Data edited successfully')
            ]);
        }

        return redirect(route('callbacks.show', $callback))->with('message', __( 'Data edited successfully' ));
    }

    /**
     * Xóa Callback.
     *
     * @param Callback $callback
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(Callback $callback)
    {
        try {
        	  $callback->delete();
        } catch ( \Exception $e ) {
            return $this->asJson( [
                'message' => "Error: {$e->getMessage()}"
            ], $e->getCode() );
        }

        return $this->asJson( [
            'message' => __('Data deleted successfully')
        ] );
    }

    /**
     * Xóa nhiều Callback.
     *
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag
     * @throws \Exception
     */
    public function destroys() {
        try {
            $ids = \request()->get( 'ids' );
            Callback::destroy( $ids );
        } catch ( \Exception $e ) {
            return $this->asJson( [
                'message' => "Error: {$e->getMessage()}"
            ], $e->getCode() );
        }

        return $this->asJson( [
            'message' => __( 'Data deleted successfully' )
        ] );
    }

    /**
     * Lấy danh sách Callback theo dạng json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function callbacks() {
        $query  = request()->get( 'query', '' );
        $page   = request()->get( 'page', 1 );
        $excludeIds = request()->get( 'excludeIds', [] );
        $offset = ( $page - 1 ) * 10;
        $callbacks  = Callback::query()->select( [ 'id', 'name' ] );

        $callbacks->andFilterWhere( [
            [ 'name', 'like', $query ],
            ['id', '!=', $excludeIds]
        ]);

        $totalCount = $callbacks->count();
        $callbacks = $callbacks->offset($offset)->limit(10)->get();

        return $this->asJson( [
            'total_count' => $totalCount,
            'items'       => $callbacks->toArray(),
        ] );
    }

    public function exportExcel(Request $request)
    {
        $filters = $request->all();

        return (new CallbackExport($filters))->download('callbacks_' . time() . '.xlsx');
    }
}