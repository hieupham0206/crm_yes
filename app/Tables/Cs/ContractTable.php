<?php

namespace App\Tables\Cs;

use App\Enums\ContractState;
use App\Models\Contract;
use App\Tables\DataTable;

class ContractTable extends DataTable
{
    public function getColumn(): string
    {
        $column = $this->column;

        switch ($column) {
            case '1':
                $column = 'contracts.contract_no';
                break;
            case '2':
                $column = 'contracts.';
                break;
            default:
                $column = 'contracts.id';
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
        $contracts    = $this->getModels();
        $dataArray    = [];
        $modelName    = (new Contract)->classLabel(true);

        $canUpdateContract = can('update-contract');
        $canDeleteContract = can('delete-contract');

        /** @var Contract[] $contracts */
        foreach ($contracts as $contract) {
            $btnEdit = $htmlChangeStatus = $btnDelete = '';

            if ($canUpdateContract) {
                $btnEdit = ' <a href="' . route('contracts.edit', $contract, false) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Edit') . '">
					<i class="fa fa-edit"></i>
				</a>';
            }

            if ($canDeleteContract) {
                $btnDelete = ' <button type="button" data-title="' . __('Delete') . ' ' . $modelName . ' ' . $contract->name . ' !!!" class="btn btn-sm btn-danger btn-delete m-btn m-btn--icon m-btn--icon-only m-btn--pill"
                data-url="' . route('contracts.destroy', $contract, false) . '" title="' . __('Delete') . '">
                    <i class="fa fa-trash"></i>
                </button>';
            }

            $htmlChangeStatus = '
				<button type="button" data-state="'.ContractState::CANCEL.'" data-message="' . __('Do you want to continue?') . '" data-url="' . route('contracts.change_state', $contract->id, false) . '" 
				class="btn btn-sm btn-danger btn-change-status m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Cancel') . '">
							<i class="fa fa-trash"></i>
						</button>
				<button type="button" data-state="'.ContractState::REFUND.'" data-message="' . __('Do you want to continue?') . '" data-url="' . route('contracts.change_state', $contract->id, false) . '" 
				class="btn btn-sm btn-danger btn-change-status m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Refresh') . '">
							<i class="fa fa-exchange-alt"></i>
						</button>
				<button type="button" data-state="'.ContractState::PROBLEM.'" data-message="' . __('Do you want to continue?') . '" data-url="' . route('contracts.change_state', $contract->id, false) . '" 
				class="btn btn-sm btn-danger btn-change-status m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Problem') . '">
							<i class="fa fa-bug"></i>
						</button>
				<button type="button" data-state="'.ContractState::CREDIT_CARD.'" data-message="' . __('Do you want to continue?') . '" data-url="' . route('contracts.change_state', $contract->id, false) . '" 
				class="btn btn-sm btn-danger btn-change-status m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Making CreditCard') . '">
							<i class="fa fa-credit-card"></i>
						</button>
				<button type="button" data-state="'.ContractState::DONE.'" data-message="' . __('Do you want to continue?') . '" data-url="' . route('contracts.change_state', $contract->id, false) . '" 
				class="btn btn-sm btn-success btn-change-status m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Done') . '">
							<i class="fa fa-check"></i>
						</button>';

            $paid = $contract->payment_details()->sum('total_paid_real');
            $debt = $contract->amount - $paid;
            $dataArray[] = [
//                '<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="' . $contract->id . '"><span></span></label>',
                $contract->contract_no,
                optional($contract->member)->name,
                optional($contract->member)->phone,
                $contract->membership_text,
                number_format($contract->amount),
                number_format($debt),
                optional($contract->created_at)->format('d-m-Y'),
                $contract->limit,

                '<a href="' . route('contracts.show', $contract, false) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('View') . '">
					<i class="fa fa-eye"></i>
				</a>' . $htmlChangeStatus . $btnEdit . $btnDelete,
            ];
        }

        return $dataArray;
    }

    /**
     * @return Contract[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getModels()
    {
        $contracts = Contract::query()->with(['member', 'payment_details']);

        $this->totalFilteredRecords = $this->totalRecords = $contracts->count();

        if ($this->isFilterNotEmpty) {
            $contracts->filters($this->filters);

            $this->totalFilteredRecords = $contracts->count();
        }

        return $contracts->limit($this->length)->offset($this->start)
                         ->orderBy($this->column, $this->direction)->get();
    }
}