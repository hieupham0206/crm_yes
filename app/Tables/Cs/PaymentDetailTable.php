<?php

namespace App\Tables\Cs;

use App\Models\PaymentDetail;
use App\Tables\DataTable;

class PaymentDetailTable extends DataTable {
	public function getColumn(): string {
		$column = $this->column;

		switch ( $column ) {
		    case '1': $column = 'payment_details.total_paid_deal'; break;case '2': $column = 'payment_details.'; break;
			default:
				$column = 'payment_details.id';
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
		$paymentDetails       = $this->getModels();
		$dataArray    = [];
		$modelName    = (new PaymentDetail)->classLabel(true);

        $canUpdatePaymentDetail = can('update-paymentDetail');
        $canDeletePaymentDetail = can('delete-paymentDetail');

		/** @var PaymentDetail[] $paymentDetails */
		foreach ( $paymentDetails as $paymentDetail ) {
		    $btnEdit = $btnDelete = '';

		    if ($canUpdatePaymentDetail) {
		        $btnEdit = ' <a href="' . route( 'payment_details.edit', $paymentDetail, false ) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __( 'Edit' ) . '">
					<i class="fa fa-edit"></i>
				</a>';
		    }

		    if ($canDeletePaymentDetail) {
                $btnDelete = ' <button type="button" data-title="'.__('Delete').' ' . $modelName . ' ' . $paymentDetail->name . ' !!!" class="btn btn-sm btn-danger btn-delete m-btn m-btn--icon m-btn--icon-only m-btn--pill"
                data-url="' . route( 'payment_details.destroy', $paymentDetail, false ) . '" title="' . __( 'Delete' ). '">
                    <i class="fa fa-trash"></i>
                </button>';
            }

			$dataArray[] = [
				'<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="'.$paymentDetail->id.'"><span></span></label>',
				$paymentDetail->total_paid_deal,

				'<a href="' . route( 'payment_details.show', $paymentDetail, false ) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __( 'View' ) . '">
					<i class="fa fa-eye"></i>
				</a>' . $btnEdit . $btnDelete
			];
		}

		return $dataArray;
	}

	/**
	 * @return PaymentDetail[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
	 */
	public function getModels() {
		$paymentDetails = PaymentDetail::query();

		$this->totalFilteredRecords = $this->totalRecords = $paymentDetails->count();

        if ($this->isFilterNotEmpty) {
            $paymentDetails->filters($this->filters);

            $this->totalFilteredRecords = $paymentDetails->count();
        }

		return $paymentDetails->limit( $this->length )->offset( $this->start )
		                 ->orderBy( $this->column, $this->direction )->get();
	}
}