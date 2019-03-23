<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 8/6/2018
 * Time: 3:38 PM
 */

namespace App\Exports;

use App\Enums\EventDataState;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\EventData;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class EventDataRecepExport implements FromView
{
    use Exportable;

    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $eventDatas = EventData::query()->with(['lead', 'to', 'rep', 'appointment'])->doesntHave('contracts');

        if (! empty($this->filters['phone'])) {
            $eventDatas->whereHas('lead', function ($q) {
                $q->andFilterWhere(['phone', 'like', $this->filters['phone']]);
            });
        }

        return view('business.event_datas._template_export', [
            'eventDatas' => $eventDatas->get(),
            'eventData'  => new EventData(),
        ]);
    }
}