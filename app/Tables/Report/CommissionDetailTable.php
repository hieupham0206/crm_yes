<?php

namespace App\Tables\Cs;

use App\Models\Commission;
use App\Models\PaymentDetail;
use App\Tables\DataTable;

class CommissionDetailTable extends DataTable
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
        $commissions    = $this->getModels();
        $dataArray    = [];
//        $modelName    = (new Contract)->classLabel(true);
//
//        $canUpdateContract = can('update-contract');
//        $canDeleteContract = can('delete-contract');

        /** @var Commission[] $commissions */
        foreach ($commissions as $commission) {
            /** @var PaymentDetail $firstPaymentDetail */
            $firstPaymentDetail = $commission->payment_details()->first();

            $dataArray[] = [
                $commission->contract_no,
                optional($commission->member)->name,
                number_format($commission->amount),
                $commission->amount - (17000 + $firstPaymentDetail->payment_cost->cost),
                'REP',
                '% REP,',
                'SM/TO',
                '% SM/TO,',
                'CS',
                '% CS,',
                'CSM',
                '% CSM,',
                'TEle trong hop dong',
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
        $commissions = Commission::query()->with(['member', 'payment_details'])->where('state', 1);

        $this->totalFilteredRecords = $this->totalRecords = $commissions->count();

        if ($this->isFilterNotEmpty) {
            $commissions->filters($this->filters);

            $this->totalFilteredRecords = $commissions->count();
        }

        return $commissions->limit($this->length)->offset($this->start)
                         ->orderBy($this->column, $this->direction)->get();
    }
}