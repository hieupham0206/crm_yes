<?php

namespace App\Tables\Cs;

use App\Enums\EventDataState;
use App\Models\EventData;
use App\Tables\DataTable;

class EventDataCsTable extends DataTable
{
    public function getColumn(): string
    {
        $column = $this->column;

        switch ($column) {
            case '1':
                $column = 'event_datas.created_at';
                break;
            case '2':
                $column = 'event_datas.to';
                break;
            case '3':
                $column = 'event_datas.rep';
                break;
            default:
                $column = 'event_datas.id';
                break;
        }

        return $column;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getData(): array
    {
        $this->column = $this->getColumn();
        $eventDatas   = $this->getModels();
        $dataArray    = [];
//        $modelName    = (new EventData)->classLabel(true);

//        $canUpdateEventData = can('update-eventData');
//        $canDeleteEventData = can('delete-eventData');

        /** @var EventData[] $eventDatas */
        foreach ($eventDatas as $eventData) {
//            $btnEdit = $btnDelete = '';
//
//            if ($canUpdateEventData) {
//                $btnEdit = ' <a href="' . route('event_data_receps.edit', $eventData, false) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Edit') . '">
//					<i class="fa fa-edit"></i>
//				</a>';
//            }
//
//            if ($canDeleteEventData) {
//                $btnDelete = ' <button type="button" data-title="' . __('Delete') . ' ' . $modelName . ' ' . $eventData->name . ' !!!" class="btn btn-sm btn-danger btn-delete m-btn m-btn--icon m-btn--icon-only m-btn--pill"
//                data-url="' . route('event_data_receps.destroy', $eventData, false) . '" title="' . __('Delete') . '">
//                    <i class="fa fa-trash"></i>
//                </button>';
//            }

            $lead        = $eventData->lead;
            $leadName    = $lead->name;
            $leadPhone   = $lead->phone;
            $dataArray[] = [
//                '<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="' . $eventData->id . '"><span></span></label>',
//                "<a class='m-link m--font-brand' href='javascript:void(0)'>{$eventData->created_at->format('d-m-Y H:i:s')}</a>",
                $eventData->created_at->format('d-m-Y H:i:s'),
                $leadName,
                $leadPhone,
                $eventData->voucher_code,
                optional($eventData->to)->username,
                optional($eventData->rep)->username,
                optional($eventData->user)->username,
//                $lead->state_text,
                ' <a href="' . route('contracts.create', ['eventDataId' => $eventData->id], false) . '" class="btn btn-sm btn-primary m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('View') . '">
						    <i class="fa fa-plus"></i></a>'
            ];
        }

        return $dataArray;
    }

    /**
     * @return EventData[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getModels()
    {
        $eventDatas = EventData::query()->with(['lead', 'to', 'rep'])->doesntHave('contracts')->where('state', EventDataState::DEAL);

        $this->totalFilteredRecords = $this->totalRecords = $eventDatas->count();

        if ($this->isFilterNotEmpty) {
            $eventDatas->filters($this->filters);

            if ($this->filters['phone']) {
                $eventDatas->whereHas('lead', function ($q) {
                    $q->andFilterWhere(['phone', 'like', $this->filters['phone']]);
                });
            }
            $this->totalFilteredRecords = $eventDatas->count();
        }

        return $eventDatas->limit($this->length)->offset($this->start)
                          ->orderBy($this->column, $this->direction)->get();
    }
}