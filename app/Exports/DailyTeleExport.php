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
use App\Models\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;

class DailyTeleExport implements FromView
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
        $users = User::where('username', '<>', 'admin')->whereKeyNot(auth()->id())
                     ->with([
                         'appointments',
                         'appointments.dealEvents',
                         'appointments.busyEvents',
                         'appointments.overflowEvents',
                         'appointments.noRepEvents',
                         'roles',
                     ])->withCount(['appointments'])->role(['Tele Marketer', 'Tele Leader', 'REP', 'TO']);

        if ($this->filters) {
            $users->filters($this->filters);

            $roleId = $this->filters['role_id'];
            if (isValueNotEmpty($roleId)) {
                $users->role($roleId);
            }
        }

        $users = $users->get();

        return view('report.daily_teles._template_export', [
            'users' => $users,
            'user'  => new User(),
        ]);
    }
}