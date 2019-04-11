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

        $canCrateEventDataCs = can('create-eventDataCs');
//        $canDeleteEventData = can('delete-eventData');

        /** @var EventData[] $eventDatas */
        foreach ($eventDatas as $eventData) {
            $btnCreate = '';
//
//            if ($canUpdateEventData) {
//                $btnEdit = ' <a href="' . route('event_data_receps.edit', $eventData, false) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Edit') . '">
//					<i class="fa fa-edit"></i>
//				</a>';
//            }
//
            if ($canCrateEventDataCs) {
                $btnCreate = ' <a href="' . route('contracts.create', ['eventDataId' => $eventData->id], false) . '" class="btn btn-sm btn-primary m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('View') . '">
						    <i class="fa fa-plus"></i></a>';
            }

            $lead        = $eventData->lead;
            $leadName    = $lead->name;
            $leadPhone   = $lead->phone;
            $dataArray[] = [
//                '<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="' . $eventData->id . '"><span></span></label>',
//                "<a class='m-link m--font-brand' href='javascript:void(0)'>{$eventData->created_at->format('d-m-Y H:i:s')}</a>",
                $eventData->created_at->format('H:i:s'),
                $eventData->created_at->format('d-m-Y'),
                $leadName,
                $leadPhone,
                $eventData->code,
                $eventData->appointment->code,
                optional($eventData->to)->name,
                optional($eventData->cs)->name,
                optional($eventData->rep)->name,
                optional($eventData->user)->name,
//                $lead->state_text,
                $btnCreate,
            ];
        }

        return $dataArray;
    }

    /**
     * @return EventData[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getModels()
    {
        $userId     = auth()->id();
        $eventDatas = EventData::query()->with(['lead', 'to', 'rep'])->doesntHave('contracts')->where('state', EventDataState::DEAL)->where(function ($q) use ($userId) {
            $q->whereNull('cs_id')->orWhere('cs_id', $userId);
        });

        $this->totalFilteredRecords = $this->totalRecords = $eventDatas->count();

        if ($this->isFilterNotEmpty) {
            $eventDatas->filters($this->filters);

            if (! empty($this->filters['phone'])) {
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