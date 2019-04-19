@php /** @var \App\Models\Commission $contract */
$breadcrumbs = ['breadcrumb' => 'contracts.index'];
@endphp

@extends("$layout.app")

@push('scripts')
    <script src="{{ asset('js/cs/contracts/index.js') }}"></script>
@endpush

@section('title', $contract->classLabel())

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            @include('layouts.partials.index_header', ['modelName' => $contract->classLabel(true), 'model' => 'contract', 'createUrl' => route('contracts.create'), 'buttons' => [
                [
                    'route' => route('contracts.export_excel'),
                    'text'  => __('Export excel'),
                    'icon'  => 'fa fa-file-excel',
                    'btnClass' => 'btn-brand btn-export-excel d-none d-sm-block',
                    'isLink' => true,
                    'canDo' => true,
                ],
            ]])
            <div class="m-portlet__body">
                @include('layouts.partials.search', ['form' => view('cs.contracts._search')->with('contract', $contract)->with('eventData', $eventData)])
                <table class="table table-borderless table-hover nowrap" id="table_contracts" width="100%">
                    <thead>
                        <tr>
                            {{--<th width="5%"><label class="m-checkbox m-checkbox--all m-checkbox--solid m-checkbox--brand"><input type="checkbox"><span></span></label></th>--}}
                            <th>{{ $contract->label('contract_no') }}</th>
                            <th>{{ $contract->label('lead_name') }}</th>
                            <th>{{ $contract->label('phone') }}</th>
                            <th>{{ $contract->label('membership') }}</th>
                            <th>{{ $contract->label('value') }}</th>
                            <th>{{ $contract->label('debt') }}</th>
                            <th>{{ $contract->label('start_date') }}</th>
                            <th>{{ $contract->label('limit') }}</th>
                            <th>{{ $contract->label('state') }}</th>
                            <th>@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection