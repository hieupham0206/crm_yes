@php /** @var \App\Models\Appointment $appointment */
$breadcrumbs = ['breadcrumb' => 'appointments.create', 'model' => $appointment];
@endphp

@extends("$layout.app")

@push('scripts')
	<script src="{{ asset('js/business/appointments/form.js') }}"></script>
@endpush

@section('title', __('action.Create Model', ['model' => $appointment->classLabel(true)]))

@section('content')
    <div class="m-content">
        <div class="m-portlet">
			@include('business.appointments._form')
        </div>
    </div>
@endsection