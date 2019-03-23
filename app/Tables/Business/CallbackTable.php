<?php

namespace App\Tables\Business;

use App\Models\Callback;
use App\Tables\DataTable;

class CallbackTable extends DataTable
{
    public function getColumn(): string
    {
        $column = $this->column;

        switch ($column) {
            case '1':
                $column = 'callbacks.lead_id';
                break;
            case '2':
                $column = 'callbacks.user_id';
                break;
            case '3':
                $column = 'callbacks.call_date';
                break;
            default:
                $column = 'callbacks.id';
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
        $callbacks    = $this->getModels();
        $dataArray    = [];
        $modelName    = (new Callback)->classLabel(true);

        $canUpdateCallback = can('update-callback');
        $canDeleteCallback = can('delete-callback');

        /** @var Callback[] $callbacks */
        foreach ($callbacks as $callback) {
            $btnEdit = $btnDelete = '';
            $lead    = $callback->lead;

//            if ($canUpdateCallback) {
//                $btnEdit = ' <a href="' . route('callbacks.edit', $callback, false) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Edit') . '">
//					<i class="fa fa-edit"></i>
//				</a>';
//            }

            if ($canDeleteCallback) {
                $btnDelete = ' <button type="button" data-title="' . __('Delete') . ' ' . $modelName . ' của ' . $lead->name . ' !!!" class="btn btn-sm btn-danger btn-delete m-btn m-btn--icon m-btn--icon-only m-btn--pill"
                data-url="' . route('callbacks.destroy', $callback, false) . '" title="' . __('Delete') . '">
                    <i class="fa fa-trash"></i>
                </button>';
            }

            $dataArray[] = [
//                '<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="' . $callback->id . '"><span></span></label>',
                $callback->user->name,
                $lead->name,
                $lead->phone,
                optional($callback->callback_datetime)->format('d-m-Y H:i:s'),
                $callback->state_text,
                $lead->comment,
//                '<a href="' . route('callbacks.show', $callback, false) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('View') . '">
//					<i class="fa fa-eye"></i>
//				</a>' .
                $btnDelete,
            ];
        }

        return $dataArray;
    }

    /**
     * @return Callback[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getModels()
    {
        $callbacks = Callback::query()->with(['lead', 'user'])->authorize();

        $this->totalFilteredRecords = $this->totalRecords = $callbacks->count();

        if ($this->isFilterNotEmpty) {
            $callbacks->filters($this->filters)->dateBetween([$this->filters['from_date'], $this->filters['to_date']], 'callback_datetime');

            if ( ! empty($this->filters['phone'])) {
                $phone = $this->filters['phone'];
                $callbacks->whereHas('lead', function ($q) use ($phone) {
                    $q->where('phone', $phone);
                });
            }

            $this->totalFilteredRecords = $callbacks->count();
        }

        return $callbacks->limit($this->length)->offset($this->start)
                         ->orderBy($this->column, $this->direction)->get();
    }
}