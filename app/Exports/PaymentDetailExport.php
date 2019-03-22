<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 8/6/2018
 * Time: 3:38 PM
 */

namespace App\Exports;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\PaymentDetail;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentDetailExport implements FromView
{
    use Exportable;

    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $fromDate       = $this->filters['from_date'] ?? '';
        $toDate         = $this->filters['to_date'] ?? '';

        $paymentDetails = PaymentDetail::query()->filters($this->filters)->dateBetween([$fromDate, $toDate])->with(['payment_cost', 'contract']);

        $contractNo = ! empty($this->filters['contract_no']) ? $this->filters['contract_no'] : '';
        if ($contractNo) {
            $paymentDetails->whereHas('contract', function (Builder $p) use ($contractNo) {
                $p->where('contract_no', 'like', "%$contractNo%");
            });
        }
        $phone      = ! empty($this->filters['phone']) ? $this->filters['phone'] : '';
        $memberName = ! empty($this->filters['name']) ? $this->filters['name'] : '';

        if ($phone || $memberName) {
            $paymentDetails->whereHas('contract.member', function (Builder $p) use ($phone, $memberName) {
                $p->where('phone', 'like', "%$phone%");
                if ($memberName) {
                    $p->where('name', 'like', "%$memberName");
                }
            });
        }

        return view('cs.payment_details._template_export', [
            'paymentDetails' => $paymentDetails->get(),
            'paymentDetail'  => new PaymentDetail(),
        ]);
    }
}