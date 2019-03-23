<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 8/6/2018
 * Time: 3:38 PM
 */

namespace App\Exports;

use App\Models\Appointment;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\HistoryCall;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class HistoryCallExport implements FromView
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
        $historyCalls = HistoryCall::query()->filters($this->filters)->with(['user', 'lead']);

        if ( ! empty($this->filters['phone'])) {
            $phone = $this->filters['phone'];
            $historyCalls->whereHas('lead', function ($q) use ($phone) {
                $q->where('phone', $phone);
            });
        }

        return view('business.history_calls._template_export', [
            'historyCalls' => $historyCalls->get(),
            'historyCall'  => new Appointment(),
        ]);
    }
}