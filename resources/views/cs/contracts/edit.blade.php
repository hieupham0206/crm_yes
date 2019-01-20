@php /** @var \App\Models\Contract $contract */
$breadcrumbs = ['breadcrumb' => 'contracts.edit', 'model' => $contract];
@endphp

@extends("$layout.app")

@push('scripts')
	<script src="{{ asset('js/cs/contracts/form.js') }}"></script>
@endpush

@section('title', __('action.Edit Model', ['model' => $contract->classLabel(true)]))

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            @include('cs.contracts._form_edit')
        </div>
    </div>
@endsection