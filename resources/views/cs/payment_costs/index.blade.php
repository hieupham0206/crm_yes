@php /** @var \App\Models\PaymentCost $paymentCost */
$breadcrumbs = ['breadcrumb' => 'payment_costs.index'];
@endphp@extends("$layout.app")

@push('scripts')
    <script src="{{ asset('js/cs/payment_costs/index.js') }}"></script>
@endpush

@section('title', $paymentCost->classLabel())

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            @include('layouts.partials.index_header', ['modelName' => $paymentCost->classLabel(true), 'model' => 'payment_cost', 'createUrl' => route('payment_costs.create')])
            <div class="m-portlet__body">
                @include('layouts.partials.search', ['form' => view('cs.payment_costs._search')->with('paymentCost', $paymentCost)])
                <table class="table table-borderless table-hover nowrap" id="table_payment_costs" width="100%">
                    <thead>
                    <tr>
                        {{--<th width="5%"><label class="m-checkbox m-checkbox--all m-checkbox--solid m-checkbox--brand"><input type="checkbox"><span></span></label></th>--}}
                        <th>{{ $paymentCost->label('name') }}</th>
                        <th>{{ $paymentCost->label('bank_name') }}</th>
                        <th>{{ $paymentCost->label('payment_method') }}</th>
                        <th>{{ $paymentCost->label('cost') }}</th>
                        <th>@lang('Actions')</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection