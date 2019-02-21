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
                $appointment->created_at->format('d-m-Y H:i:s'),
                optional($appointment->user)->name,
                $appointment->lead->name,

                $appointment->spouse_name,
                optional($appointment->lead)->phone,
                $appointment->spouse_phone,
                $appointment->code,
                $appointment->appointment_datetime->format('d-m-Y'),
                $appointment->appointment_datetime->format('H:i'),
                $appointment->history_calls_count,

                optional($appointment->lead)->comment,
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

            $createdAt = $this->filters['created_at'];
            if ($createdAt) {
                $appointments->whereDate('created_at', date('Y-m-d', strtotime($createdAt)));
            }

            $this->totalFilteredRecords = $appointments->count();
        }

        return $appointments->limit($this->length)->offset($this->start)
                            ->orderBy($this->column, $this->direction)->get();
    }
}