<?php

namespace App\Http\Controllers\Cs;

use App\Http\Controllers\Controller;
use App\Models\EventData;
use App\Tables\Cs\EventDataCsTable;
use App\Tables\TableFacade;
use Illuminate\Http\Request;

class EventDataCsController extends Controller
{
    /**
     * Tên dùng để phân quyền
     * @var string
     */
    protected $name = 'eventData';

    /**
     * Hiển thị trang danh sách EventData.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('cs.event_datas.index')->with('eventData', new EventData);
    }

    /**
     * Lấy danh sách EventData cho trang table ở trang index
     * @return string
     */
    public function table()
    {
        return (new TableFacade(new EventDataCsTable()))->getDataTable();
    }

    /**
     * Trang tạo mới EventData.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('cs.event_datas.create', [
            'eventData' => new EventData,
            'action'    => route('event_datas.store'),
        ]);
    }

    /**
     * Lưu EventData mới.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $requestData = $request->all();
        $eventData   = EventData::create($requestData);

        if ($request->wantsJson()) {
            return $this->asJson([
                'message' => __('Data created successfully'),
            ]);
        }

        return redirect(route('event_datas.show', $eventData))->with('message', __('Data created successfully'));
    }

    /**
     * Trang xem chi tiết EventData.
     *
     * @param  EventData $eventData
     *
     * @return \Illuminate\View\View
     */
    public function show(EventData $eventData)
    {
        return view('cs.event_datas.show', compact('eventData'));
    }

    /**
     * Trang cập nhật EventData.
     *
     * @param EventData $eventDataRecep
     *
     * @return \Illuminate\View\View
     */
    public function edit(EventData $eventDataRecep)
    {
        return view('cs.event_datas.edit', [
            'eventData' => $eventDataRecep,
            'method'    => 'put',
            'action'    => route('event_datas.update', $eventDataRecep),
        ]);
    }

    /**
     * Cập nhật EventData tương ứng.
     *
     * @param \Illuminate\Http\Request $request
     * @param  EventData $eventData
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, EventData $eventData)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        $requestData = $request->all();

        if ( ! $request->has('hot_bonus')) {
            $requestData['hot_bonus'] = -1;
        }

        $eventData->update($requestData);

        if ($request->wantsJson()) {
            return $this->asJson([
                'message' => __('Data edited successfully'),
            ]);
        }

        return redirect(route('event_datas.show', $eventData))->with('message', __('Data edited successfully'));
    }

    /**
     * Xóa EventData.
     *
     * @param EventData $eventData
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(EventData $eventData)
    {
        try {
            $eventData->delete();
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
     * Xóa nhiều EventData.
     *
     * @return mixed|\Symfony\Component\HttpFoundation\ParameterBag
     * @throws \Exception
     */
    public function destroys()
    {
        try {
            $ids = \request()->get('ids');
            EventData::destroy($ids);
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
     * Lấy danh sách EventData theo dạng json
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function eventData()
    {
        $query      = request()->get('query', '');
        $page       = request()->get('page', 1);
        $excludeIds = request()->get('excludeIds', []);
        $offset     = ($page - 1) * 10;
        $eventData  = EventData::query()->select(['id', 'name']);

        $eventData->andFilterWhere([
            ['name', 'like', $query],
            ['id', '!=', $excludeIds],
        ]);

        $totalCount = $eventData->count();
        $eventData  = $eventData->offset($offset)->limit(10)->get();

        return $this->asJson([
            'total_count' => $totalCount,
            'items'       => $eventData->toArray(),
        ]);
    }
}