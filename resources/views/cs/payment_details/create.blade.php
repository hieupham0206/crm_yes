@php /** @var \App\Models\PaymentDetail $paymentDetail */
$breadcrumbs = ['breadcrumb' => 'payment_details.create', 'model' => $paymentDetail];
@endphp

@extends("$layout.app")

@push('scripts')
	<script src="{{ asset('js/cs/payment_details/form.js') }}"></script>
@endpush

@section('title', __('action.Create Model', ['model' => $paymentDetail->classLabel(true)]))

@section('content')
    <div class="m-content">
        <div class="m-portlet">
			@include('cs.payment_details._form')
        </div>
    </div>
@endsection