@php /** @var \App\Models\PaymentDetail $paymentDetail */
$breadcrumbs = ['breadcrumb' => 'payment_details.index'];
@endphp

@extends("$layout.app")

@push('scripts')
    <script src="{{ asset('js/cs/payment_details/index.js') }}"></script>
@endpush

@section('title', $paymentDetail->classLabel())

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            @include('layouts.partials.index_header', ['modelName' => $paymentDetail->classLabel(true), 'model' => 'payment_detail', 'createUrl' => ''])
            <div class="m-portlet__body">
                @include('layouts.partials.search', ['form' => view('cs.payment_details._search')->with('paymentDetail', $paymentDetail)])
                <table class="table table-borderless table-hover nowrap" id="table_payment_details" width="100%">
                    <thead>
                        <tr>
                            {{--<th width="5%"><label class="m-checkbox m-checkbox--all m-checkbox--solid m-checkbox--brand"><input type="checkbox"><span></span></label></th>--}}
                            <th>{{ $paymentDetail->label('pay_date') }}</th>
                            <th>{{ $paymentDetail->label('amount') }}</th>
                            <th>{{ $paymentDetail->label('pay_date_real') }}</th>
                            <th>{{ $paymentDetail->label('amount') }}</th>
                            <th>{{ $paymentDetail->label('cost') }}</th>
                            <th>@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection