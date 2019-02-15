<?php

namespace App\Tables\Business;

use App\Enums\Confirmation;
use App\Models\Appointment;
use App\Tables\DataTable;

class AppointmentTable extends DataTable
{
    public function getColumn(): string
    {
        $column = $this->column;

        switch ($column) {
            case '1':
                $column = 'appointments.user_id';
                break;
            case '2':
                $column = 'appointments.lead_id';
                break;
            default:
                $column = 'appointments.id';
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
        $this->column = $this->getColumn();
        $appointments = $this->getModels();
        $dataArray    = [];
        $modelName    = (new Appointment)->classLabel(true);
//
        $canUpdateAppointment = can('update-appointment');
        $canDeleteAppointment = can('delete-appointment');

        /** @var Appointment[] $appointments */
        foreach ($appointments as $appointment) {
            $btnEdit = $btnDelete = '';

            if ($canUpdateAppointment) {
                $btnEdit = ' <a href="' . route('appointments.edit', $appointment, false) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('Edit') . '">
					<i class="fa fa-edit"></i>
				</a>';
            }

            if ($canDeleteAppointment) {
                $btnDelete = ' <button type="button" data-title="' . __('Delete') . ' ' . $modelName . ' ' . $appointment->name . ' !!!" class="btn btn-sm btn-danger btn-delete m-btn m-btn--icon m-btn--icon-only m-btn--pill"
                data-url="' . route('appointments.destroy', $appointment, false) . '" title="' . __('Delete') . '">
                    <i class="fa fa-trash"></i>
                </button>';
            }

            $dataArray[] = [
//                '<label class="m-checkbox m-checkbox--single m-checkbox--solid m-checkbox--brand"><input type="checkbox" value="' . $appointment->id . '"><span></span></label>',
                optional($appointment->user)->name,
//                optional($appointment->user->roles[0])->name,
                $appointment->appointment_datetime->format('d-m-Y H:i'),
                $appointment->lead->name,

                $appointment->code,
                $appointment->spouse_name,
                $appointment->spouse_phone,
                optional($appointment->lead)->comment,
                $appointment->is_show_up_text,
                $appointment->is_queue_text,

                optional($appointment->lead)->phone,
//                $appointment->created_at->format('d-m-Y H:i:s'),
                $appointment->created_at->format('d-m-Y H:i:s'),
                $appointment->history_calls_count,

//                '<a href="' . route('appointments.show', $appointment, false) . '" class="btn btn-sm btn-brand m-btn m-btn--icon m-btn--icon-only m-btn--pill" title="' . __('View') . '">
//					<i class="fa fa-eye"></i>
//				</a>' .
                $btnEdit . $btnDelete,
            ];
        }

        return $dataArray;
    }

    /**
     * @return Appointment[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getModels()
    {
        $appointments = Appointment::query()->with(['user', 'lead'])->withCount(['history_calls'])->where('state', Confirmation::YES)->authorize();

        $this->totalFilteredRecords = $this->totalRecords = $appointments->count();

        if ($this->isFilterNotEmpty) {
            $appointments->filters($this->filters)->dateBetween([$this->filters['from_date'], $this->filters['to_date']], 'appointment_datetime');

            $departmentId = $this->filters['department_id'];
            if ($departmentId) {
                $appointments->whereHas('user.departments', function ($department) use ($departmentId) {
                    return $department->whereKey($departmentId);
                });
            }

            $this->totalFilteredRecords = $appointments->count();
        }

        return $appointments->limit($this->length)->offset($this->start)
                            ->orderBy($this->column, $this->direction)->get();
    }
}