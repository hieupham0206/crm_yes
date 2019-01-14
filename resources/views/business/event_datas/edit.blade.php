@php /** @var \App\Models\EventData $eventData */
$breadcrumbs = ['breadcrumb' => 'event_datas.edit', 'model' => $eventData];
@endphp

@extends("$layout.app")

@push('scripts')
	<script src="{{ asset('js/business/event_data_receps/form.js') }}"></script>
@endpush

@section('title', __('action.Edit Model', ['model' => $eventData->classLabel(true)]))

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            @include('business.event_datas._form')
        </div>
    </div>
@endsection