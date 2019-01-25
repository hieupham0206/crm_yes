<?php
/**
 * Created by PhpStorm.
 * User: ADMIN
 * Date: 1/23/2018
 * Time: 2:01 PM
 */

namespace App\Tables\Report;

use App\Enums\EventDataState;
use App\Models\Appointment;
use App\Models\EventData;
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

        return $this->getModels();
    }

    /**
     * @return array
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
                     ])->withCount(['appointments'])->role(['REP'])->get();

        $datas = [];

        foreach ($users as $user) {

            $appointments = $user->appointments();
            $eventDatas   = EventData::query();

            if ($this->isFilterNotEmpty) {
                $appointments->dateBetween([$this->filters['from_date'], $this->filters['to_date']]);
                $eventDatas->dateBetween([$this->filters['from_date'], $this->filters['to_date']]);
            }
            $appointments = $appointments->get();
            $eventDatas   = $eventDatas->get();

            $totalAppointment = $appointments->count();
            $totalEventData   = $eventDatas->count();

            $totalAppointmentTele     = $appointments->filter(function (Appointment $app) {
                return $app->user->hasRole(['Tele Marketer', 'Tele Leader']);
            })->count();
            $totalAppointmentRep      = $appointments->filter(function (Appointment $app) {
                return $app->user->hasRole(['REP']);
            })->count();
            $totalAppointmentHasEvent = $appointments->filter(function (Appointment $app) {
                return $app->events;
            })->count();
            $totalAppointmentNoRep    = $totalAppointmentHasEvent - ($totalAppointment - $totalAppointmentRep);
            $totalAppointmentNQ       = $appointments->filter(function (Appointment $app) {
                return $app->is_queue == 0;
            })->count();
            $totalAppointmentQueue    = $appointments->filter(function (Appointment $app) {
                return $app->is_queue == 1;
            })->count();
            $totalAppointmentTo       = $appointments->filter(function (Appointment $app) {
                return $app->user->hasRole(['SALE DECK MANAGER (SDM)', 'TO']);
            })->count();
            $toRate                   = ($totalAppointmentTo / $totalEventData) * 100;
            $totalEventDataDeal       = $eventDatas->filter(function (EventData $event) {
                return $event->state == EventDataState::DEAL;
            })->count();
            $totalEventDataNotDeal    = $eventDatas->filter(function (EventData $event) {
                return $event->state == EventDataState::NOT_DEAL;
            })->count();
            $dealRate                 = ($totalEventDataDeal / $totalEventData) * 100;
            $ambassador = $appointments->filter(function (Appointment $app) use ($user) {
                return $app->user_id === $user->id;
            })->count();//Tổng APP của từng nhan viên

            $datas[] = [
                $totalAppointmentTele,
                'digital',
                'opc',
                $totalAppointmentRep,
                'ssref',
//            $totalAppointmentHasEvent,
                $ambassador,
                $totalAppointment,
                $totalAppointmentNoRep,
                $totalAppointmentNQ,
                $totalAppointmentQueue,
                $totalAppointmentTo,
                $toRate,
                $totalEventDataDeal,
                $totalEventDataNotDeal,
                $dealRate,
                'phone SS'
            ];
        }

        return $datas;
    }
}