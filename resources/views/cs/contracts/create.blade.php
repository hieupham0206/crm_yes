@php /** @var \App\Models\Commission $contract */
$breadcrumbs = ['breadcrumb' => 'contracts.create', 'model' => $contract];
@endphp

@extends("$layout.app")

@push('scripts')
	<script src="{{ asset('js/cs/contracts/form.js') }}"></script>
@endpush

@section('title', __('action.Create Model', ['model' => $contract->classLabel(true)]))

@section('content')
    <div class="m-content">
        <div class="m-portlet">
			@include('cs.contracts._form')
        </div>
    </div>
@endsection