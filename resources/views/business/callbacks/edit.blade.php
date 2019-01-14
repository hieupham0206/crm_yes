@php /** @var \App\Models\Callback $callback */
$breadcrumbs = ['breadcrumb' => 'callbacks.edit', 'model' => $callback];
@endphp

@extends("$layout.app")

@push('scripts')
	<script src="{{ asset('js/business/callbacks/form.js') }}"></script>
@endpush

@section('title', __('action.Edit Model', ['model' => $callback->classLabel(true)]))

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            @include('business.callbacks._form')
        </div>
    </div>
@endsection