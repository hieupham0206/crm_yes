<?php

namespace App\Tables\Cs;

use App\Models\Contract;
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
        $contracts    = $this->getModels();
        $dataArray    = [];
//        $modelName    = (new Contract)->classLabel(true);
//
//        $canUpdateContract = can('update-contract');
//        $canDeleteContract = can('delete-contract');

        /** @var Contract[] $contracts */
        foreach ($contracts as $contract) {
            /** @var PaymentDetail $firstPaymentDetail */
            $firstPaymentDetail = $contract->payment_details()->first();

            $dataArray[] = [
//                '<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="' . $contract->id . '"><span></span></label>',
                $contract->contract_no,
                optional($contract->member)->name,
                number_format($contract->amount),
                $contract->amount - (17000 + $firstPaymentDetail->payment_cost->cost),
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
     * @return Contract[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getModels()
    {
        $contracts = Contract::query()->with(['member', 'payment_details'])->where('state', 1);

        $this->totalFilteredRecords = $this->totalRecords = $contracts->count();

        if ($this->isFilterNotEmpty) {
            $contracts->filters($this->filters);

            $this->totalFilteredRecords = $contracts->count();
        }

        return $contracts->limit($this->length)->offset($this->start)
                         ->orderBy($this->column, $this->direction)->get();
    }
}