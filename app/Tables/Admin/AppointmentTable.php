<?php

namespace App\Tables\Admin;

use App\Enums\Confirmation;
use App\Enums\HistoryCallType;
use App\Models\Appointment;
use App\Tables\DataTable;
use Carbon\Carbon;

class AppointmentTable extends DataTable
{
    public function getColumn(): string
    {
        $column = $this->column;

        switch ($column) {
            default:
                $column = 'appointments.appointment_datetime';
                break;
        }

        return $column;
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function getData(): array
    {
        $this->column    = $this->getColumn();
        $this->direction = 'asc';
        $modelName       = (new Appointment)->classLabel(true);

        $canUpdateAppointment = can('update-appointment');
        $canDeleteAppointment = can('delete-appointment');

        $form = request()->get('form', 'tele_console');
        if ($form === 'tele_console') {
            $this->filters['not_qa_and_not_has_deal'] = true;
            $appointments                             = $this->getModels();
            $dataArray                                = $this->initTableTeleConsole($appointments, $canUpdateAppointment, $canDeleteAppointment, $modelName);
        } elseif ($form === 'reception_console') {
//            $this->filters['today']         = true;
            $this->filters['is_show_up']               = Confirmation::NO;
            $this->filters['appointment_of_reception'] = true;
            $appointments                              = $this->getModels(true);
            $dataArray                                 = $this->initTableReceptionConsole($appointments);
        }

        return $dataArray ?? [];
    }

    /**
     * @param bool $getAll
     *
     * @return Appointment[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getModels($getAll = false)
    {
        $appointments = Appointment::query()->with(['lead', 'user'])
                                   ->where('is_queue', '>', 1);

        if ( ! $getAll) {
            $appointments = $appointments->where('state', Confirmation::YES);
        } elseif ( ! empty($this->filters['not_qa_and_not_has_deal'])) {
            $appointments = $appointments->doesntHave('event_datas');
        }

        $this->totalFilteredRecords = $this->totalRecords = $appointments->count();

        if ($this->isFilterNotEmpty) {
            $appointments->filters($this->filters);

            if (isset($this->filters['phone']) && $this->filters['phone']) {
                $appointments->whereHas('lead', function ($q) {
                    $q->andFilterWhere(['phone', 'like', $this->filters['phone']]);
                });
            }

            $this->totalFilteredRecords = $appointments->count();
        }

        if (isset($this->filters['today'])) {
            $appointments->whereDate('appointment_datetime', Carbon::today());

            $this->totalFilteredRecords = $appointments->count();
        }

        if (isset($this->filters['appointment_of_reception'])) {
            $appointments->where('state', '!=', Confirmation::NO)->whereDoesntHave('event_datas');

            $this->totalFilteredRecords = $appointments->count();
        }

        return $appointments->limit($this->length)->offset($this->start)
                            ->orderBy($this->column, $this->direction)->get();
    }

    /**
     * @param $appointments
     * @param $canUpdateAppointment
     * @param $canDeleteAppointment
     * @param $modelName
     *
     * @return array
     */
    private function initTableTeleConsole($appointments, $canUpdateAppointment, $canDeleteAppointment, $modelName): array
    {
        $dataArray = [];
        /** @var Appointment[] $appointments */
        foreach ($appointments as $key => $appointment) {
            $btnEdit = $btnDelete = $btnCall = '';

//            if ($canUpdateAppointment) {
//                $btnEdit = ' <a href="' . route('appointments.edit', $appointment, false) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Edit') . '">
//					<i class="fa fa-edit"></i>
//				</a>';
//            }

//            if ($canUpdateAppointment) {
//                $btnEdit = ' <button data-id="' . $appointment->id . '" data-url="' . route('leads.edit_appointment_time',
//                        $appointment) . '" class="btn btn-sm btn-brand btn-edit-datetime m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Edit') . '">
//					<i class="fa fa-edit"></i>
//				</button>';
//            }
//
//            if ($canDeleteAppointment) {
//                $btnDelete = ' <button type="button" data-route="appointments" data-title="' . __('Delete') . ' ' . $modelName . ' ' . $appointment->name . ' !!!" class="btn btn-sm btn-danger btn-delete m-btn m-btn--icon m-btn--icon-only m-btn--pill"
//                data-url="' . route('appointments.destroy', $appointment, false) . '" title="' . __('Delete') . '">
//                    <i class="fa fa-trash"></i>
//                </button>';
//            }

            $btnCall             = ' <button id="btn_appointment_call_' . $appointment->id . '_' . $appointment->lead_id . '"  type="button" data-id="' . $appointment->id . '" data-lead-id="' . $appointment->lead_id . '" data-type-call="' .
                                   HistoryCallType::APPOINTMENT . '" 
                class="btn btn-sm btn-appointment-call btn-primary m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Call') . '">
                    <i class="fa fa-phone"></i>
                </button>';
            $appointmentDateText = "<span class='span-datetime'>" . optional($appointment->appointment_datetime)->format('d-m-Y H:i') . '</span>';
            if ($appointment->appointment_datetime) {
                $dateRemain = now()->diffInDays($appointment->appointment_datetime);

                if ($dateRemain == 1) {
                    $appointmentDateText = "<span class='span-datetime m--font-danger'>" . optional($appointment->appointment_datetime)->format('d-m-Y') . '</span>';
                }
            }

            $lead        = $appointment->lead;
            $dataArray[] = [
//                optional($lead)->title,
                ++$key,
                optional($lead)->phone,
                optional($lead)->name,
                optional($appointment->appointment_datetime)->format('H:i'),
                $appointmentDateText,
                optional($lead)->comment,

                $btnCall . $btnEdit . $btnDelete,
            ];
        }

        return $dataArray;
    }

    /**
     * @param $appointments
     *
     * @return array
     */
    private function initTableReceptionConsole($appointments): array
    {
        $dataArray = [];
        /** @var Appointment[] $appointments */
        foreach ($appointments as $appointment) {
            $appointmentDateText = "<span class='span-datetime'>" . optional($appointment->appointment_datetime)->format('d-m-Y H:i') . '</span>';
            if ($appointment->appointment_datetime) {
                $dateRemain = now()->diffInDays($appointment->appointment_datetime);

                if ($dateRemain == 1) {
                    $appointmentDateText = "<span class='span-datetime m--font-danger'>" . optional($appointment->appointment_datetime)->format('d-m-Y H:i') . '</span>';
                }
            }

            $lead        = $appointment->lead;
            $user        = $appointment->user;
            $dataArray[] = [
                $appointmentDateText,
                $user ? $user->roles[0]->name : '',
                "<a class='link-lead-name m-link m--font-brand' href='javascript:void(0)' data-appointment-id='{$appointment->id}' data-lead-id='{$appointment->lead_id}'>{$lead->name}</a>",
                optional($lead)->phone,
                $appointment->code,
                optional($user)->name,
                optional($lead)->comment,

//                $btnCall . $btnEdit . $btnDelete,
            ];
        }

        return $dataArray;
    }
}