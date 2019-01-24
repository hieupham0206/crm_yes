<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 1/23/2018
 * Time: 2:01 PM
 */

namespace App\Tables\Report;

use App\Models\Appointment;
use App\Models\User;
use App\Tables\DataTable;
use Carbon\Carbon;

class DailySaleReportTable extends DataTable
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

        /** @var User[] $users */
        foreach ($users as $user) {

            $appointments       = $user->appointments;
            $totalAppointments  = $user->appointments_count;
            $totalQueue         = $appointments->filter(function (Appointment $app) {
                return $app->is_queue == 1;
            })->count();
            $totalNotQueue      = $appointments->filter(function (Appointment $app) {
                return $app->is_queue == 0;
            })->count();
            $totalNoRep         = $appointments->sum(function (Appointment $app) {
                return $app->noRepEvents->count();
            });
            $totalOverflow      = $appointments->sum(function (Appointment $app) {
                return $app->overflowEvents->count();
            });
            $totalCancel        = $appointments->filter(function (Appointment $app) {
                return $app->state == -1;
            })->count();
            $totalReAppointment = $appointments->sum(function (Appointment $app) {
                return $app->busyEvents->count();
            });
            $total3pmEvent      = $appointments->filter(function (Appointment $app) {
                return $app->appointment_datetime->isSameHour(Carbon::createFromTime(13, 0, 0));
            })->count();
            $totalDeal          = $appointments->sum(function (Appointment $app) {
                return $app->dealEvents->count();
            });
//            $rate               = $totalQueue > 0 ? $totalDeal / $totalQueue * 0.1 : 0;
            $rate        = $totalAppointments > 0 ? $totalQueue / $totalAppointments * 0.1 : 0;
            $dataArray[] = [
                '<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="' . $user->id . '"><span></span></label>',
                $user->name,
                optional($user->roles[0])->name,
                optional($user->first_day_work)->format('d-m-Y'),
                $totalQueue,//queue
                $totalNotQueue,//not queue
                $totalNoRep,//queue nhung k co ten rep
                $totalOverflow,//overflow+
                $total3pmEvent,//3pm app
                $totalCancel,//cancel
                $totalReAppointment,//re-app
                $totalQueue + $totalNotQueue + $totalNoRep + $totalOverflow + $totalCancel + $totalReAppointment + $total3pmEvent,//sum(Q; NQ; No Rep; Overflow; CXL; Re-App; 3PM Event)
                $totalDeal,//deal
                $rate,//rate: deal/Q
            ];
        }

        return $dataArray;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getModels()
    {
        $appointments = Appointment::with(['user'])->get();

        $totalAppointmentTele = $appointments->filter(function(Appointment $app) {
            return $app->user->hasRole(['Tele Marketer', 'Tele Leader']);
        })->count();
        $totalAppointmentRep = $appointments->filter(function(Appointment $app) {
            return $app->user->hasRole(['REP']);
        })->count();
        $totalAppointment = $appointments->count();
        $totalAppointmentNoRep = $appointments->count();


        $this->totalFilteredRecords = $this->totalRecords = $appointments->count();

        if ($this->isFilterNotEmpty) {
            $appointments->filters($this->filters);

            $this->totalFilteredRecords = $appointments->count();
        }

        return $appointments->limit($this->length)
                     ->offset($this->start)
                     ->orderBy($this->column, $this->direction)->get();
    }
}