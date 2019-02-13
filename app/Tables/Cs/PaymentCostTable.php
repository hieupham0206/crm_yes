<?php

namespace App\Tables\Cs;

use App\Models\PaymentCost;
use App\Tables\DataTable;

class PaymentCostTable extends DataTable
{
    public function getColumn(): string
    {
        $column = $this->column;

        switch ($column) {
            case '1':
                $column = 'payment_costs.name';
                break;
            case '2':
                $column = 'payment_costs.payment_cost';
                break;
            default:
                $column = 'payment_costs.id';
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
        $paymentCosts = $this->getModels();
        $dataArray    = [];
        $modelName    = (new PaymentCost)->classLabel(true);

        $canUpdatePaymentCost = can('update-paymentCost');
        $canDeletePaymentCost = can('delete-paymentCost');

        /** @var PaymentCost[] $paymentCosts */
        foreach ($paymentCosts as $paymentCost) {
            $btnEdit = $btnDelete = '';

            if ($canUpdatePaymentCost) {
                $btnEdit = ' <a href="' . route('payment_costs.edit', $paymentCost, false) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Edit') . '">
					<i class="fa fa-edit"></i>
				</a>';
            }

            if ($canDeletePaymentCost && $paymentCost->payment_method != 5) {
                $btnDelete = ' <button type="button" data-title="' . __('Delete') . ' ' . $modelName . ' ' . $paymentCost->name . ' !!!" class="btn btn-sm btn-danger btn-delete m-btn m-btn--icon m-btn--icon-only m-btn--pill"
                data-url="' . route('payment_costs.destroy', $paymentCost, false) . '" title="' . __('Delete') . '">
                    <i class="fa fa-trash"></i>
                </button>';
            }

            $dataArray[] = [
//                '<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="' . $paymentCost->id . '"><span></span></label>',
                $paymentCost->name,
                $paymentCost->bank_name,
                $paymentCost->payment_method_text,
                number_format($paymentCost->cost),

//                '<a href="' . route('payment_costs.show', $paymentCost, false) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('View') . '">
//					<i class="fa fa-eye"></i>
//				</a>' .
                $btnEdit . $btnDelete,
            ];
        }

        return $dataArray;
    }

    /**
     * @return PaymentCost[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getModels()
    {
        $paymentCosts = PaymentCost::query();

        $this->totalFilteredRecords = $this->totalRecords = $paymentCosts->count();

        if ($this->isFilterNotEmpty) {
            $paymentCosts->filters($this->filters);

            $this->totalFilteredRecords = $paymentCosts->count();
        }

        return $paymentCosts->limit($this->length)->offset($this->start)
                            ->orderBy($this->column, $this->direction)->get();
    }
}