@php /** @var \App\Models\PaymentCost $paymentCost */
$breadcrumbs = ['breadcrumb' => 'payment_costs.create', 'model' => $paymentCost];
@endphp

@extends("$layout.app")

@push('scripts')
	<script src="{{ asset('js/cs/payment_costs/form.js') }}"></script>
@endpush

@section('title', __('action.Create Model', ['model' => $paymentCost->classLabel(true)]))

@section('content')
    <div class="m-content">
        <div class="m-portlet">
			@include('cs.payment_costs._form')
        </div>
    </div>
@endsection