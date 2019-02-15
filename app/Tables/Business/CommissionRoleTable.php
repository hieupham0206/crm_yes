<?php

namespace App\Tables\Business;

use App\Enums\CommissionRoleSpecification;
use App\Models\Commission;
use App\Models\CommissionRole;
use App\Tables\DataTable;

class CommissionRoleTable extends DataTable
{
    public function getColumn(): string
    {
        $column = $this->column;

        switch ($column) {
//            case '1':
//                $column = 'contract_no';
//                break;
//            case '2':
//                $column = '';
//                break;
            default:
                $column = 'id';
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
        $roleDatas    = $this->getModels();
        $dataArray    = [];
//        $modelName    = (new Contract)->classLabel(true);
//
//        $canUpdateContract = can('update-contract');
//        $canDeleteContract = can('delete-commission');
        $commissionRoles    = CommissionRole::all();
        $commissionRoleSdms = $commissionRoles->filter(function ($r) {
            return $r->role_id == 7;
        });

        foreach ($roleDatas as $roleData) {
            $buttonDelete = $dealCompleted = $commisionBonus = $level = $buttonSaveCommission = '';
            $roleId       = $roleData['id'];

            $spec                 = ! empty($roleData['spec']) ? (int) $roleData['spec'] : '';
            $commissionRole       = $commissionRoles->filter(function ($r) use ($roleId, $spec) {
                if ($spec) {
                    return $r->role_id == $roleId && $r->specification == $spec;
                }

                return $r->role_id == $roleId;
            })->first();
            $commissionRoleId     = $commissionRole ? $commissionRole->id : '';
            $buttonSaveCommission = ' <button type="button" data-spec="' . $spec . '" data-commission-role-id="' . $commissionRoleId . '" data-role-id="' . $roleId . '" data-url="' . route('commission_roles.store') . '"
            class="btn btn-sm btn-success btn-save-commission-role m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Delete') . '">
							<i class="fa fa-save"></i>
						</button>';
//            if ($canDeleteContract) {
//                $buttonDelete = ' <button type="button" class="btn btn-sm btn-danger btn-delete m-btn m-btn--icon m-btn--icon-only m-btn--pill"
//						data-title="' . __('Delete') . ' ' . $modelName . ' ' . $commissionRole->contract_no . ' !!!" data-url="' . route('users.destroy', $commissionRole->id, false) . '" title="' . __('Delete') . '">
//							<i class="fa fa-trash"></i>
//						</button>';
//            }

            $percentCommission = $commissionRole ? $commissionRole->percent_commission : '';
            //nếu role SDM thi moi hiện
            if ($roleId == 7) {
                $commissionRoleSdm = $commissionRoleSdms->pop();
                $roleLevel         = optional($commissionRoleSdm)->level;
                $level             = '<input class="form-control txt-level" value="' . $roleLevel . '"/>';

                if ($commissionRoleSdm) {
                    $percentCommission = $commissionRoleSdm->percent_commission;

                    $buttonSaveCommission = ' <button type="button" data-spec="' . $spec . '" data-commission-role-id="' . $commissionRoleSdm->id . '" data-role-id="' . $roleId . '" data-url="' . route('commission_roles.store') . '"
            class="btn btn-sm btn-success btn-save-commission-role m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Delete') . '">
							<i class="fa fa-save"></i>
						</button>';
                }
            }

            //nếu role TO thi moi hiện
            if ($roleId == 8) {
                $percentCommissionBonus = $commissionRole ? $commissionRole->percent_commission_bonus : '';
                $commisionBonus         = '<input class="form-control txt-bonus-commission" value="' . $percentCommissionBonus . '"/>';
            }

            //nếu role TO thi moi hiện
            if ($roleId == 6) {
                $roleDealCompleted = $commissionRole ? $commissionRole->deal_completed : '';
                $dealCompleted     = '<input class="form-control txt-deal-completed" value="' . $roleDealCompleted . '"/>';
            }
            $dataArray[] = [
//                '<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="' . $contract->id . '"><span></span></label>',
                $roleData['name'],
                $spec ? CommissionRoleSpecification::getDescription($spec) : '',
                $level,
                '<input class="form-control txt-percent-commission" value="' . $percentCommission . '"/>',
                $commisionBonus,
                $dealCompleted,

                $buttonSaveCommission . $buttonDelete,
            ];
        }

        return $dataArray;
    }

    /**
     * @return Commission[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getModels()
    {
//        $roles = Role::query()->whereKeyNot(1);
//
//        $this->totalFilteredRecords = $this->totalRecords = $roles->count();
//
//        if ($this->isFilterNotEmpty) {
//            $roles->filters($this->filters);
//
//            $this->totalFilteredRecords = $roles->count();
//        }
//
//        return $roles->orderBy($this->column, $this->direction)->get();

        return [
            [
                'name' => 'REP',
                'id'   => '9',
                'spec' => '1',
            ],
            [
                'name' => 'REP',
                'id'   => '9',
                'spec' => '2',
            ],
            [
                'name' => 'REP',
                'id'   => '9',
                'spec' => '3',
            ],

            [
                'name' => 'SM/TO',
                'id'   => '8',
                'spec' => '1',
            ],
            [
                'name' => 'SM/TO',
                'id'   => '8',
                'spec' => '2',
            ],
            [
                'name' => 'SM/TO',
                'id'   => '8',
                'spec' => '3',
            ],

            [
                'name' => 'CS',
                'id'   => '12',
            ],
            [
                'name' => 'CS MANAGER',
                'id'   => '11',
            ],

            [
                'name' => 'SDM',
                'id'   => '7',
            ],
            [
                'name' => 'SDM',
                'id'   => '7',
            ],
            [
                'name' => 'SDM',
                'id'   => '7',
            ],

            [
                'name' => 'Tele',
                'id'   => '6',
            ],
        ];
    }
}