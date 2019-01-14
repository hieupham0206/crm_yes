@php /** @var \App\Models\EventData $eventData */
$breadcrumbs = ['breadcrumb' => 'event_datas.create', 'model' => $eventData];
@endphp

@extends("$layout.app")

@push('scripts')
	<script src="{{ asset('js/cs/event_data_cs/form.js') }}"></script>
@endpush

@section('title', __('action.Create Model', ['model' => $eventData->classLabel(true)]))

@section('content')
    <div class="m-content">
        <div class="m-portlet">
			@include('business.event_datas._form')
        </div>
    </div>
@endsection