<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 1/23/2018
 * Time: 2:01 PM
 */

namespace App\Tables\Report;

use App\Models\Appointment;
use App\Models\EventData;
use App\Models\User;
use App\Tables\DataTable;
use Carbon\Carbon;

class DailyTeleReportTable extends DataTable
{
    public function getColumn(): string
    {
        $column = $this->column;

        switch ($column) {
            case '1':
                $column = 'username';
                break;
            case '2':
                $column = 'email';
                break;
            case '3':
                $column = 'state';
                break;
            case '4':
                $column = 'last_login';
                break;
            default:
                $column = 'users.id';
                break;
        }

        return $column;
    }

    public function getData(): array
    {
        $this->column = $this->getColumn();
        $users        = $this->getModels();
        $dataArray    = [];

        $fromDate = $this->filters['from_date'];
        $toDate   = $this->filters['to_date'];

        if ($fromDate) {
            $fromDate = date('d', strtotime($this->filters['from_date']));
        } else {
            $fromDate = 1;
        }

        if ($toDate) {
            $toDate = date('d', strtotime($this->filters['to_date']));
        } else {
            $toDate = (new Carbon('last day of last month'))->endOfMonth()->day;
        }

        /** @var User[] $users */
        foreach ($users as $user) {

            $appointments      = $user->appointments;
            $totalAppointments = $user->appointments_count;
            $totalQueue        = $appointments->filter(function (Appointment $app) {
                return $app->is_queue == 1;
            })->count();
            $totalShow         = $appointments->filter(function (Appointment $app) {
                return $app->is_show_up == 1;
            })->count();
            $totalNotQueue     = $appointments->filter(function (Appointment $app) {
                return $app->is_queue == 0;
            })->count();
            $totalNoRep        = $appointments->sum(function (Appointment $app) {
                return $app->noRepEvents->count();
            });
            $totalOverflow     = $appointments->sum(function (Appointment $app) {
                return $app->overflowEvents->count();
            });
//            $totalCancel        = $appointments->filter(function (Appointment $app) {
//                return $app->state == -1;
//            })->count();
            $totalReAppointment = $appointments->sum(function (Appointment $app) {
                return $app->busyEvents->count();
            });
            $total3pmEvent      = $appointments->filter(function (Appointment $app) use ($fromDate, $toDate) {
                $time    = Carbon::now();
                $morning = Carbon::create($time->year, 1, $fromDate, 14, 30, 0);
                $evening = Carbon::create($time->year, $time->month, $toDate, 16, 0, 0);

                return $app->appointment_datetime->between($morning, $evening);
            })->count();
            $totalDeal          = $appointments->sum(function (Appointment $app) use ($user) {
                return $app->dealEvents->filter(function (EventData $event) use ($user) {
                    return $event->rep_id === $user->id;
                })->count();
            });
//            $rate               = $totalQueue > 0 ? $totalDeal / $totalQueue * 0.1 : 0;
            $rateDeal    = $totalAppointments > 0 ? $totalQueue / $totalAppointments * 0.1 : 0;
            $rateApp     = $totalAppointments > 0 ? $totalShow / $totalAppointments * 0.1 : 0;
            $dataArray[] = [
//                '<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="' . $user->id . '"><span></span></label>',
                $user->name,
                optional($user->roles[0])->name,
//                optional($user->first_day_work)->format('d-m-Y'),
                $totalQueue,//queue
                $totalNotQueue,//not queue
                $totalNoRep,//queue nhung k co ten rep
                $totalOverflow,//overflow+
                $total3pmEvent,//3pm app
//                $totalCancel,//cancel (cxl)
                $totalReAppointment,//re-app
                $totalAppointments,//total-app
                number_format($rateApp, 2),
                $totalQueue + $totalNotQueue,//sum(Q; NQ; No Rep; Overflow; CXL; Re-App; 3PM Event)
                $totalDeal,//deal
                number_format($rateDeal, 2),//rate: deal/Q
            ];
        }

        return $dataArray;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getModels()
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
                     ])->withCount(['appointments'])->role(['Tele Marketer', 'Tele Leader', 'REP', 'TO']);

        $this->totalFilteredRecords = $this->totalRecords = $users->count();

        if ($this->isFilterNotEmpty) {
            $users->filters($this->filters);

            $roleId = $this->filters['role_id'];
            if (isValueNotEmpty($roleId)) {
                $users->role($roleId);
            }

            $this->totalFilteredRecords = $users->count();
        }

        return $users->limit($this->length)
                     ->offset($this->start)
                     ->orderBy($this->column, $this->direction)->get();
    }
}