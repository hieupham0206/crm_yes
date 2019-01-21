<?php

namespace App\Tables\Cs;

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
            $btnEdit = $btnDelete = '';

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

            $dataArray[] = [
//                '<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="' . $contract->id . '"><span></span></label>',
                $contract->contract_no,
                optional($contract->member)->name,
                optional($contract->member)->phone,
                $contract->membership_text,
                $contract->amount,
                'debt',
                $contract->created_at->format('d-m-Y'),
                $contract->limit,

                '<a href="' . route('contracts.show', $contract, false) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('View') . '">
					<i class="fa fa-eye"></i>
				</a>' . $btnEdit . $btnDelete,
            ];
        }

        return $dataArray;
    }

    /**
     * @return Contract[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getModels()
    {
        $contracts = Contract::query()->with(['member']);

        $this->totalFilteredRecords = $this->totalRecords = $contracts->count();

        if ($this->isFilterNotEmpty) {
            $contracts->filters($this->filters);

            $this->totalFilteredRecords = $contracts->count();
        }

        return $contracts->limit($this->length)->offset($this->start)
                         ->orderBy($this->column, $this->direction)->get();
    }
}