@php /** @var \App\Models\HistoryCall $historyCall */
$breadcrumbs = ['breadcrumb' => 'history_calls.edit', 'model' => $historyCall];
@endphp

@extends("$layout.app")

@push('scripts')
	<script src="{{ asset('js/business/history_calls/form.js') }}"></script>
@endpush

@section('title', __('action.Edit Model', ['model' => $historyCall->classLabel(true)]))

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            @include('business.history_calls._form')
        </div>
    </div>
@endsection