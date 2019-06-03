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
use App\Models\Contract;
use App\Models\EventData;
use App\Models\User;
use App\Tables\DataTable;

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
                         'appointments.user.roles' => function ($q) {
                             $q->dateBetween([$this->filters['from_date'], $this->filters['to_date']]);
                         },
                         'roles',
                     ])->role(['REP'])->filters($this->filters)->get();
        $datas = [];

        $eventDatas = EventData::query()->with(['appointment.user.roles', 'contracts.payment_details']);

        if ($this->isFilterNotEmpty) {
            $eventDatas->dateBetween([$this->filters['from_date'], $this->filters['to_date']]);
        }
        $eventDatas = $eventDatas->get();
        foreach ($users as $user) {
            $appointments = $user->appointments;

//            $totalAppointment = $user->appointments_count;
//            $totalEventData   = $eventDatas->count();

            $eventDataWithRep = $eventDatas->filter(function (EventData $event) use ($user) {
                return $event->rep_id === $user->id;
            });
//
            $totalAppointmentTele = $appointments->filter(function (Appointment $app) {
                return $app->user->hasRole(['Tele Marketer', 'Tele Leader']);
            })->count();

            $totalOnPoint            = $eventDataWithRep->filter(function (EventData $event) {
                $appointment = $event->appointment;

                return $appointment->user
                       && $appointment->user->hasRole(['AGENT']);
            })->count();
            $totalPrivateAppointment = $appointments->filter(function (Appointment $app) use ($user) {
                return $app->user_id === $user->id;
            })->count();
//
            $ambassador = $eventDataWithRep->filter(function (EventData $event) use ($user) {
                return $event->appointment->ambassador === $user->id;
            })->count();

            $total = $totalAppointmentTele + $totalOnPoint + $totalPrivateAppointment;
//
            $totalAppointmentNQ    = $appointments->filter(function (Appointment $app) use ($user) {
                return $app->is_queue == 0 && ($app->user_id === $user->id);
            })->count();
            $totalAppointmentQueue = $appointments->filter(function (Appointment $app) use ($user) {
                return $app->is_queue == 1 && ($app->user_id === $user->id);
            })->count();
            $totalAppointmentTo    = $eventDataWithRep->filter(function (EventData $event) {
                return $event->to_id;
            })->count();
            $totalEventDataDeal    = $eventDatas->filter(function (EventData $event) use ($user) {
                return $event->state == EventDataState::DEAL && $event->rep_id == $user->id;
            })->count();
//
            $moneyIn = $eventDataWithRep->sum(function (EventData $event) {
                return $event->contracts->sum(function (Contract $contract) {
                    $paymentDetails = $contract->payment_details;

                    return isset($paymentDetails[0]) ? $paymentDetails[0]->total_paid_real : 0;
                });
            });

            $datas[] = [
                $user->name,
                $totalAppointmentTele ?? 0,
                $totalOnPoint ?? 0,
                $totalPrivateAppointment ?? 0,
                $ambassador ?? 0,
                $total ?? 0,
                $totalAppointmentNQ ?? 0,
                $totalAppointmentQueue ?? 0,
                $totalAppointmentTo ?? 0,
                $totalEventDataDeal ?? 0,
                $moneyIn ? number_format($moneyIn) : 0,
            ];
        }

        $this->totalRecords = $this->totalFilteredRecords = count($datas);

        return $datas;
    }
}