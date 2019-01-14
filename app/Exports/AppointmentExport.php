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
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class AppointmentExport implements FromView
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
        return view('business.appointments._template_export', [
            'appointments' => Appointment::query()->filters($this->filters)->with(['user', 'lead'])->withCount(['history_calls'])->get(),
            'appointment'  => new Appointment(),
        ]);
    }
}