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
//        $this->filters = $filters;

        $this->filters = \Cache::get('daily_tele_filter', []);
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $users = User::where('username', '<>', 'admin')->whereKeyNot(auth()->id())
                     ->with([
                         'appointments'                => function ($q) {
                             $q->dateBetween([$this->filters['from_date'], $this->filters['to_date']]);
                         },
                         'appointments.dealEvents'     => function ($q) {
                             $q->dateBetween([$this->filters['from_date'], $this->filters['to_date']]);
                         },
                         'appointments.busyEvents'     => function ($q) {
                             $q->dateBetween([$this->filters['from_date'], $this->filters['to_date']]);
                         },
                         'appointments.overflowEvents' => function ($q) {
                             $q->dateBetween([$this->filters['from_date'], $this->filters['to_date']]);
                         },
                         'appointments.noRepEvents'    => function ($q) {
                             $q->dateBetween([$this->filters['from_date'], $this->filters['to_date']]);
                         },
                         'roles',

                     ])->withCount(['appointments', 'history_calls'])->role(['Tele Marketer', 'Tele Leader', 'REP', 'TO']);

        if ($this->filters) {
            $users->filters($this->filters);

            $roleId = $this->filters['role_id'];
            if (isValueNotEmpty($roleId)) {
                $users->role($roleId);
            }
        }

        $users = $users->get();

        $historyCalls = \DB::table('history_calls')
                           ->select(\DB::raw('SUM(time_of_call) as total_call, user_id'))
                           ->groupBy(\DB::raw('user_id'))
                           ->get();

        return view('report.daily_teles._template_export', [
            'users'        => $users,
            'user'         => new User(),
            'historyCalls' => $historyCalls,
        ]);
    }
}