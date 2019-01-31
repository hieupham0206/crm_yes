@php /** @var \App\Models\User $user */
$breadcrumbs = ['breadcrumb' => 'users.index', 'label' => 'Commission detail'];
@endphp@extends("$layout.app")

@push('scripts')
    <script src="{{ asset('js/report/commission_details/index.js') }}"></script>
@endpush

@section('title', 'Commission detail')

@section('content')
    <div class="m-content">
        <div class="m-portlet">
{{--            @include('layouts.partials.index_header', ['modelName' => 'commmision_detail', 'model' => 'commmision_detail', 'createUrl' => '', 'editUrl' => ''])--}}
            <div class="m-portlet__body">
                @include('layouts.partials.search', ['form' => view('report.commission_details._search')])
                <table class="table table-borderless table-hover nowrap" id="table_commission_detail_report"  width="100%">
                    <thead>
                    <tr>
                        <th>{{ $contract->label('contract_no') }}</th>
                        <th>{{ $contract->label('lead_name') }}</th>
                        <th>Total contract</th>
                        <th>Net contract</th>
                        <th>{{ $contract->label('total_commission') }}</th>

                        <th>{{ $contract->label('rep') }}</th>
                        <th>%</th>
                        <th>{{ $contract->label('sm/to') }}</th>
                        <th>%</th>
                        <th>{{ $contract->label('cs') }}</th>
                        <th>%</th>
                        <th>{{ $contract->label('csm') }}</th>
                        <th>%</th>
                        <th>Tip</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

