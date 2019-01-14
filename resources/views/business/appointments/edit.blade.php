@php /** @var \App\Models\Appointment $appointment */
$breadcrumbs = ['breadcrumb' => 'appointments.edit', 'model' => $appointment];
@endphp

@extends("$layout.app")

@push('scripts')
	<script src="{{ asset('js/business/appointments/form.js') }}"></script>
@endpush

@section('title', __('action.Edit Model', ['model' => $appointment->classLabel(true)]))

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            @include('business.appointments._form')
        </div>
    </div>
@endsection