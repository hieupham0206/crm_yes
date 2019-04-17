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
use App\Models\Lead;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class LeadExport implements FromView
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
        return view('business.leads._template_export', [
            'leads' => Lead::query()->filters($this->filters)->with(['user', 'lead'])->withCount(['history_calls'])->get(),
            'lead'  => new Lead(),
        ]);
    }
}