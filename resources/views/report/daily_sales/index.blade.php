@php /** @var \App\Models\User $user */
$breadcrumbs = ['breadcrumb' => 'users.index', 'label' => 'Daily sale report'];
@endphp@extends("$layout.app")

@push('scripts')
    <script src="{{ asset('js/report/daily_sales/index.js') }}"></script>
@endpush

@section('title', 'Daily tele report')

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            @include('layouts.partials.index_header', ['modelName' => $user->classLabel(true), 'model' => 'user', 'createUrl' => '', 'editUrl' => ''])
            <div class="m-portlet__body">
                @include('layouts.partials.search', ['form' => view('report.daily_sales._search', ['user' => $user])])
                <table class="table table-borderless table-hover nowrap" id="table_daily_sale_report"  width="100%">
                    <thead>
                    <tr>
                        {{--<th width="5%"><label class="m-checkbox m-checkbox--all m-checkbox--solid m-checkbox--brand"><input type="checkbox"><span></span></label></th>--}}
                        <th>Tele name</th>
                        <th>Tele</th>
                        <th>Digital</th>
                        <th>OPC</th>
                        <th>Private</th>
                        <th>SSRef</th>
                        <th>Ambassador</th>
                        <th>Total</th>
                        {{--<th>{{ $user->label('no_rep') }}</th>--}}
                        <th>NQ</th>
                        <th>Q</th>
                        <th>TO</th>
                        <th>TO Rate</th>
                        <th>{{ $user->label('deal') }}</th>
                        {{--<th>{{ $user->label('cxl') }}</th>--}}
                        <th>{{ $user->label('deal_rate') }}</th>
                        <th>{{ $user->label('phone ss') }}</th>
                        {{--<th>@lang('Actions')</th>--}}
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

