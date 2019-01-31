<?php

namespace App\Tables\Cs;

use App\Models\Commission;
use App\Models\User;
use App\Tables\DataTable;

class CommissionUserTable extends DataTable
{
    public function getColumn(): string
    {
        $column = $this->column;

        switch ($column) {
//            case '1':
//                $column = 'contracts.contract_no';
//                break;
//            case '2':
//                $column = 'contracts.';
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
        $users        = $this->getModels();
        $dataArray    = [];
        $contracts    = Commission::with(['member'])->dateBetween([$this->filters['from_date'], $this->filters['to_date']])->get();
//        $modelName    = (new Contract)->classLabel(true);
//
//        $canUpdateContract = can('update-contract');
//        $canDeleteContract = can('delete-contract');

        /** @var Commission[] $users */
        foreach ($users as $user) {
            $roles                     = $user->roles;
            $totalContractOfUser       = $contracts->filter(function ($contract) use ($user) {
                return $contract->member->user_id === $user->id;
            })->count();
            $totalContractOfPrivate       = $contracts->filter(function ($contract) use ($user) {
                $eventData = $contract->event_data;
                $user1     = $eventData->appointment->user;

                return $user1->roles[0] === 'TELE MARKETER' && $eventData->rep_id === $user->id;
            })->count();
            $totalContractOfTele       = $contracts->filter(function ($contract) use ($user) {
                return $contract->event_data->appointment->user_id === $user->id;
            })->count();
            $totalContractOfAmbassador = $contracts->filter(function ($contract) use ($user) {
                return $contract->event_data->appointment->ambassador === $user->id;
            })->count();
            $totalCommission           = $user->commissions->sum('net_total');

            $dataArray[] = [
//                '<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="' . $contract->id . '"><span></span></label>',
                $roles ? $roles[0]->name : '',
                $user->name,
                $totalContractOfUser,
                $totalContractOfPrivate,
                $totalContractOfTele,
                $totalContractOfAmbassador,
                number_format($totalCommission),
            ];
        }

        return $dataArray;
    }

    /**
     * @return Commission[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getModels()
    {
        $users = User::query()->with([
//            'contract' => function ($c) {
//                return $c->where('state', 1);
//            },
        ])->role(['REP']);

        $this->totalFilteredRecords = $this->totalRecords = $users->count();

        if ($this->isFilterNotEmpty) {
            $users->filters($this->filters);

            $this->totalFilteredRecords = $users->count();
        }

        return $users->limit($this->length)->offset($this->start)
                     ->orderBy($this->column, $this->direction)->get();
    }
}