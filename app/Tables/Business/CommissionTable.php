<?php

namespace App\Tables\Business;

use App\Models\Contract;
use App\Models\Role;
use App\Tables\DataTable;

class CommissionTable extends DataTable
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
        $roles    = $this->getModels();
        $dataArray    = [];
        $modelName    = (new Contract)->classLabel(true);
//
//        $canUpdateContract = can('update-contract');
        $canDeleteContract = can('delete-commission');

        /** @var Role[] $roles */
        foreach ($roles as $role) {
            $buttonDelete = $level = $buttonSaveCommission = '';
            $buttonSaveCommission = ' <button type="button" class="btn btn-sm btn-success btn-save-commission m-btn m-btn--icon m-btn--icon-only m-btn--pill" 
            data-url="' . route('commission.destroy', $role->id, false) . '" title="' . __('Delete') . '">
							<i class="fa fa-trash"></i>
						</button>';
            if ($canDeleteContract) {
                $buttonDelete = ' <button type="button" class="btn btn-sm btn-danger btn-delete m-btn m-btn--icon m-btn--icon-only m-btn--pill" 
						data-title="' . __('Delete') . ' ' . $modelName . ' ' . $role->contract_no . ' !!!" data-url="' . route('users.destroy', $role->id, false) . '" title="' . __('Delete') . '">
							<i class="fa fa-trash"></i>
						</button>';
            }

            //nếu role SDM thi moi hiện
            if ($role->id == 7) {
                $level         = '<input class="form-control txt-quota"/>';
            }
            $dataArray[] = [
//                '<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="' . $contract->id . '"><span></span></label>',
                $role->user->roles[0]->name,
                'Hình thức',
                $level,
                '<input class="form-control txt-percent-commission"/>',
                '<input class="form-control txt-bonus-commission"/>',
                '<input class="form-control txt-deal-completed"/>',

                $buttonSaveCommission . $buttonDelete,
            ];
        }

        return $dataArray;
    }

    /**
     * @return Contract[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getModels()
    {
        $roles = Role::query()->whereKeyNot(1);

        $this->totalFilteredRecords = $this->totalRecords = $roles->count();

        if ($this->isFilterNotEmpty) {
            $roles->filters($this->filters);

            $this->totalFilteredRecords = $roles->count();
        }

        return $roles->orderBy($this->column, $this->direction)->get();
    }
}