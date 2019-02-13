<?php

namespace App\Tables\Cs;

use App\Models\PaymentDetail;
use App\Tables\DataTable;
use Illuminate\Database\Eloquent\Builder;

class PaymentDetailTable extends DataTable
{
    public function getColumn(): string
    {
        $column = $this->column;

        switch ($column) {
            case '1':
                $column = 'payment_details.total_paid_deal';
                break;
            case '2':
                $column = 'payment_details.';
                break;
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
    public function getData(): array
    {
        $this->column   = $this->getColumn();
        $paymentDetails = $this->getModels();
        $dataArray      = [];
//        $modelName      = (new PaymentDetail)->classLabel(true);

        $canUpdatePaymentDetail = can('update-paymentDetail');
//        $canDeletePaymentDetail = can('delete-paymentDetail');

        /** @var PaymentDetail[] $paymentDetails */
        foreach ($paymentDetails as $paymentDetail) {
            $btnEdit = '';

            if ($canUpdatePaymentDetail && ! $paymentDetail->pay_date_real) {
                $btnEdit = ' <a href="' . route('payment_details.edit', $paymentDetail, false) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Edit') . '">
					<i class="fa fa-edit"></i>
				</a>';
            }

            $dataArray[] = [
//				'<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="'.$paymentDetail->id.'"><span></span></label>',
                optional($paymentDetail->contract)->contract_no,
                optional($paymentDetail->pay_date)->format('d-m-Y'),
                number_format($paymentDetail->total_paid_deal),
                optional($paymentDetail->pay_date_real)->format('d-m-Y'),
                number_format($paymentDetail->total_paid_real),
                $paymentDetail->payment_cost->cost,

                $btnEdit,
            ];
        }

        return $dataArray;
    }

    /**
     * @return PaymentDetail[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getModels()
    {
        $paymentDetails = PaymentDetail::query()->with(['payment_cost', 'contract']);

        $this->totalFilteredRecords = $this->totalRecords = $paymentDetails->count();

        if ($this->isFilterNotEmpty) {
            $paymentDetails->filters($this->filters)->dateBetween([$this->filters['from_date'], $this->filters['to_date']]);

            $contractNo = $this->filters['contract_no'];
            if ($contractNo) {
                $paymentDetails->whereHas('contract', function (Builder $p) use ($contractNo) {
                    $p->where('contract_no', 'like', "%$contractNo%");
                });
            }
            $phone      = $this->filters['phone'];
            $memberName = $this->filters['name'];
            if ($phone || $memberName) {
                $paymentDetails->whereHas('contract.member', function (Builder $p) use ($phone, $memberName) {
                    $p->where('phone', 'like', "%$phone%");
                    if ($memberName) {
                        $p->where('name', 'like', "%$memberName");
                    }
                });
            }

            $this->totalFilteredRecords = $paymentDetails->count();
        }

        return $paymentDetails->limit($this->length)->offset($this->start)
                              ->orderBy($this->column, $this->direction)->get();
    }
}