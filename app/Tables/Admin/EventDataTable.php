<?php

namespace App\Tables\Admin;

use App\Models\EventData;
use App\Tables\DataTable;
use Illuminate\Database\Eloquent\Builder;

class EventDataTable extends DataTable
{
    public function getColumn(): string
    {
        $column = $this->column;

        switch ($column) {
//            case '1':
//                $column = 'event_datas.created_at';
//                break;
//            case '2':
//                $column = 'event_datas.to';
//                break;
//            case '3':
//                $column = 'event_datas.rep';
//                break;
            default:
                $column = 'event_datas.created_at';
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
//            $btnNotDeal = '
//				<button type="button" data-state="'.EventDataState::NOT_DEAL.'" data-message="" title="Not deal" data-title="Hủy deal khách hàng ' . $eventData->lead->name . ' !!!"
//				data-url="' . route('event_datas.change_state', $eventData->id, false) . '" class="btn btn-sm btn-danger btn-change-event-status m-btn m-btn--icon m-btn--icon-only m-btn--pill">
//							<i class="fa fa-trash"></i>
//						</button>
//			';
//            $btnDeal = '
//				<button type="button" data-state="'.EventDataState::DEAL.'" data-message="" title="Deal" data-title="Chốt deal khách hàng ' . $eventData->lead->name . ' !!!"
//				data-url="' . route('event_datas.change_state', $eventData->id, false) . '" class="btn btn-sm btn-success btn-change-event-status m-btn m-btn--icon m-btn--icon-only m-btn--pill">
//							<i class="fa fa-check"></i>
//						</button>
//			';
//            $btnBusy = '
//				<button type="button" data-state="'.EventDataState::BUSY.'" data-message="" title="Busy" data-title="Hủy deal khách hàng ' . $eventData->lead->name . ' !!!"
//				data-url="' . route('event_datas.change_state', $eventData->id, false) . '" class="btn btn-sm btn-success btn-change-event-status m-btn m-btn--icon m-btn--icon-only m-btn--pill">
//							<i class="fa fa-street-view"></i>
//						</button>
//			';
//            $btnOverflow = '
//				<button type="button" data-state="'.EventDataState::OVERFLOW.'" data-message="" title="Overflow" data-title="Hủy deal khách hàng ' . $eventData->lead->name . ' !!!"
//				data-url="' . route('event_datas.change_state', $eventData->id, false) . '" class="btn btn-sm btn-danger btn-change-event-status m-btn m-btn--icon m-btn--icon-only m-btn--pill">
//							<i class="fa fa-ban"></i>
//						</button>
//			';

//            if ($canUpdateEventData) {
//                $btnEdit = ' <a href="' . route('event_datas.edit', $eventData, false) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Edit') . '">
//					<i class="fa fa-edit"></i>
//				</a>';
//            }
//
//            if ($canDeleteEventData) {
//                $btnDelete = ' <button type="button" data-title="' . __('Delete') . ' ' . $modelName . ' ' . $eventData->name . ' !!!" class="btn btn-sm btn-danger btn-delete m-btn m-btn--icon m-btn--icon-only m-btn--pill"
//                data-url="' . route('event_datas.destroy', $eventData, false) . '" title="' . __('Delete') . '">
//                    <i class="fa fa-trash"></i>
//                </button>';
//            }

            $leadName       = $eventData->lead->name;
            $leadId         = $eventData->lead_id;
            $appointmentId  = $eventData->appointment_id;
            $appIsQueueText = $eventData->appointment->is_queue === 1 ? 'Queue' : 'Not queue';

            $toName     = optional($eventData->to)->name;
            $repName    = optional($eventData->rep)->name;
            $csName     = optional($eventData->cs)->name;

            $dataArray[]    = [
//                '<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="' . $eventData->id . '"><span></span></label>',
//                "<a class='m-link m--font-brand' href='javascript:void(0)'>{$eventData->created_at->format('d-m-Y H:i:s')}</a>",
                $eventData->created_at->format('d-m-Y H:i:s'),
                $eventData->lead->title,
                "<a class='link-event-data m-link m--font-brand' href='javascript:void(0)'  data-to-id='{$eventData->to_id}'  data-rep-id='{$eventData->rep_id}'  data-cs-id='{$eventData->cs_id}' 
data-has-bonus='{$eventData->hot_bonus}' data-appointment-id='{$appointmentId}' data-event-id='{$eventData->id}' data-lead-id='{$eventData->lead_id}'>$leadName</a>" .
                "<input value='$leadName' class='txt-lead-name' type='hidden'/>" .
                "<input value='$leadId' class='txt-lead-id' type='hidden'/>" .
                "<input value='{$eventData->note}' class='txt-event-data-note' type='hidden'/>" .
                "<input value='{$eventData->code}' class='txt-event-data-code' type='hidden'/>" .
                "<input value='{$eventData->state_name}' class='txt-event-data-state' type='hidden'/>" .
                "<input value='{$appIsQueueText}' class='txt-event-queue-text' type='hidden'/>" .
                "<input value='{$toName}' class='txt-to-name' type='hidden'/>".
                "<input value='{$repName}' class='txt-rep-name' type='hidden'/>".
                "<input value='{$csName}' class='txt-cs-name' type='hidden'/>",
                $eventData->lead->phone,
                $eventData->voucher_code,
                $eventData->note,
                optional($eventData->to)->username,
                optional($eventData->rep)->username,
//                $btnBusy . $btnOverflow . $btnDeal . $btnNotDeal
            ];
        }

        return $dataArray;
    }

    /**
     * @return EventData[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getModels()
    {
        $eventDatas = EventData::query()->with(['lead', 'to', 'rep', 'cs', 'appointment'])->doesntHave('contracts')->where(function(Builder $e) {
            $e->where('state', '>', -1)->orWhereNull('state');
        });

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