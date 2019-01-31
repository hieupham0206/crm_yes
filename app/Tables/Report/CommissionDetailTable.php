<?php

namespace App\Tables\Cs;

use App\Models\Commission;
use App\Tables\DataTable;

class CommissionDetailTable extends DataTable
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
        $commissions  = $this->getModels();
        $dataArray    = [];
//        $modelName    = (new Contract)->classLabel(true);
//
//        $canUpdateContract = can('update-contract');
//        $canDeleteContract = can('delete-contract');

        /** @var Commission[] $commissions */
        foreach ($commissions as $commission) {
            $dataArray[] = [
                $commission->contract_no,
                optional($commission->contract)->member->name,
                number_format($commission->amount),
                $commission->amount,
                '',
                '',
                '',
                '',
                '',
                '',
                '',
                '',
//                'REP',
//                '% REP,',
//                'SM/TO',
//                '% SM/TO,',
//                'CS',
//                '% CS,',
//                'CSM',
//                '% CSM,',
                '',
                250000,
            ];
        }

        return $dataArray;
    }

    /**
     * @return Commission[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getModels()
    {
        $commissions = Commission::query()->with(['user']);

        $this->totalFilteredRecords = $this->totalRecords = $commissions->count();

        if ($this->isFilterNotEmpty) {
            $commissions->filters($this->filters);

            if (! empty($this->filters['from_date'])) {
                $commissions->whereHas('contract', function($contract) {
                    $contract->dateBetween([$this->filters['from_date'], $this->filters['to_date']]);
                });
            }

            $this->totalFilteredRecords = $commissions->count();
        }

        return $commissions->limit($this->length)->offset($this->start)
                           ->orderBy($this->column, $this->direction)->get();
    }
}