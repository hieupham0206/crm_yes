<?php

namespace App\Tables\Business;

use App\Models\HistoryCall;
use App\Tables\DataTable;

class HistoryCallTable extends DataTable {
	public function getColumn(): string {
		$column = $this->column;

		switch ( $column ) {
		    case '1': $column = 'history_calls.user_id'; break;
			default:
				$column = 'history_calls.id';
				break;
		}

		return $column;
	}

    /**
     * @return array
     * @throws \ReflectionException
     */
	public function getData(): array {
		$this->column = $this->getColumn();
		$historyCalls       = $this->getModels();
		$dataArray    = [];
//		$modelName    = (new HistoryCall)->classLabel(true);

//        $canUpdateHistoryCall = can('update-historyCall');
//        $canDeleteHistoryCall = can('delete-historyCall');

		/** @var HistoryCall[] $historyCalls */
		foreach ( $historyCalls as $historyCall ) {
//		    $btnEdit = $btnDelete = '';

//		    if ($canUpdateHistoryCall) {
//		        $btnEdit = ' <a href="' . route( 'history_calls.edit', $historyCall, false ) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __( 'Edit' ) . '">
//					<i class="fa fa-edit"></i>
//				</a>';
//		    }
//
//		    if ($canDeleteHistoryCall) {
//                $btnDelete = ' <button type="button" data-title="'.__('Delete').' ' . $modelName . ' ' . $historyCall->name . ' !!!" class="btn btn-sm btn-danger btn-delete m-btn m-btn--icon m-btn--icon-only m-btn--pill"
//                data-url="' . route( 'history_calls.destroy', $historyCall, false ) . '" title="' . __( 'Delete' ). '">
//                    <i class="fa fa-trash"></i>
//                </button>';
//            }

			$dataArray[] = [
				'<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="'.$historyCall->id.'"><span></span></label>',
				$historyCall->user->name,
				$historyCall->lead->name,
                $historyCall->created_at->subSeconds($historyCall->time_of_call)->format('d-m-Y H:i:s'),
//                $historyCall->created_at->format('d-m-Y H:i:s'),
                $historyCall->state_text,
                gmdate('H:i:s', $historyCall->time_of_call),
                $historyCall->comment,
                $historyCall->call_type,

//				'<a href="' . route( 'history_calls.show', $historyCall, false ) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __( 'View' ) . '">
//					<i class="fa fa-eye"></i>
//				</a>' . $btnEdit . $btnDelete
			];
		}

		return $dataArray;
	}

	/**
	 * @return HistoryCall[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function getModels() {
		$historyCalls = HistoryCall::query()->with(['user', 'lead'])->authorize();

		$this->totalFilteredRecords = $this->totalRecords = $historyCalls->count();

        if ($this->isFilterNotEmpty) {
            $historyCalls->filters($this->filters)->dateBetween([$this->filters['from_date'], $this->filters['to_date']]);

            $this->totalFilteredRecords = $historyCalls->count();
        }

		return $historyCalls->limit( $this->length )->offset( $this->start )
		                 ->orderBy( $this->column, $this->direction )->get();
	}
}