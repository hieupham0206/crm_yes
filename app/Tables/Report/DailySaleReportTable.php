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
                     ])->withCount(['appointments', 'ambassadors'])->role(['REP'])->filters($this->filters)->get();

        $datas       = [];
        $currentUser = auth()->user();

        foreach ($users as $user) {
            $appointments = $user->appointments();
            $eventDatas   = EventData::query();

            if ($this->isFilterNotEmpty) {
                $appointments->dateBetween([$this->filters['from_date'], $this->filters['to_date']]);
                $eventDatas->dateBetween([$this->filters['from_date'], $this->filters['to_date']]);
            }
            $appointments = $appointments->get();
            $eventDatas   = $eventDatas->get();

//            $totalAppointment = $user->appointments_count;
//            $totalEventData   = $eventDatas->count();

            $eventDataWithRep = $eventDatas->filter(function (EventData $event) use ($currentUser) {
                return $event->rep_id === $currentUser->id;
            });

            $totalAppointmentTele    = $appointments->filter(function (Appointment $app) {
                return $app->user->hasRole(['Tele Marketer', 'Tele Leader']);
            })->count();
            $totalOnPoint            = $eventDataWithRep->filter(function (EventData $event) use ($currentUser) {
                return $event->appointment->user->hasRole(['AGENT']);
            })->count();
            $totalPrivateAppointment = $appointments->filter(function (Appointment $app) use ($currentUser) {
                return $app->user_id === $currentUser->id;
            })->count();

            $ambassador = $eventDataWithRep->filter(function (EventData $event) use ($currentUser) {
                return $event->appointment->ambassador === $currentUser->id;
            })->count();

            $total = $totalAppointmentTele + $totalOnPoint + $totalPrivateAppointment;

            $totalAppointmentNQ    = $appointments->filter(function (Appointment $app) use ($currentUser) {
                return $app->is_queue == 0 &&
                       ($app->user_id === $currentUser->id
//                        ||
//                        $app->event_datas()->get()->filter(function ($event) use ($currentUser) {
//                            return $event->rep_id === $currentUser->id;
//                        })->isNotEmpty()
                    );
            })->count();
            $totalAppointmentQueue = $appointments->filter(function (Appointment $app) use ($currentUser) {
                return $app->is_queue == 1 &&
                       ($app->user_id === $currentUser->id
//                        ||
//                        $app->event_datas()->get()->filter(function ($event) use ($currentUser) {
//                            return $event->rep_id === $currentUser->id;
//                        })->isNotEmpty()
                    );
            })->count();
            $totalAppointmentTo    = $eventDataWithRep->filter(function (EventData $event) use ($currentUser) {
                return $event->to_id;
            })->count();
            $totalEventDataDeal    = $eventDatas->filter(function (EventData $event) use ($currentUser) {
                return $event->state == EventDataState::DEAL;
//                       && $event->rep_id === $currentUser->id
            })->count();

            $moneyIn = $eventDataWithRep->sum(function (EventData $event) {
                return $event->contracts->sum(function(Contract $contract) {
                    return $contract->payment_details[0]->total_paid_real;
                });
            });

            $datas[] = [
                $user->name,
                $totalAppointmentTele,
                $totalOnPoint,
                $totalPrivateAppointment,
                $ambassador,
                $total,
                $totalAppointmentNQ,
                $totalAppointmentQueue,
                $totalAppointmentTo,
                $totalEventDataDeal,
                $moneyIn,
            ];
        }

        return $datas;
    }
}