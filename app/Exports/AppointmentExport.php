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
//        $this->filters = $filters;

        $this->filters = \Cache::get('appointment_filter', []);
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $appointments = Appointment::query()->with(['user', 'lead'])->withCount(['history_calls']);
        if ($this->filters) {
            $appointments->filters($this->filters)->dateBetween([$this->filters['from_date'], $this->filters['to_date']], 'appointment_datetime');

            $departmentId = $this->filters['department_id'];
            if ($departmentId) {
                $appointments->whereHas('user.departments', function ($department) use ($departmentId) {
                    return $department->whereKey($departmentId);
                });
            }

            $createdAt = $this->filters['created_at'];
            if ($createdAt) {
                $appointments->whereDate('created_at', date('Y-m-d', strtotime($createdAt)));
            }

            if ( ! empty($this->filters['phone'])) {
                $phone = $this->filters['phone'];
                $appointments->whereHas('lead', function ($q) use ($phone) {
                    $q->where('phone', $phone);
                });
            }

        }

        return view('business.appointments._template_export', [
            'appointments' => $appointments->get(),
            'appointment'  => new Appointment(),
        ]);
    }
}