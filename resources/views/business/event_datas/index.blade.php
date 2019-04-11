@php /** @var \App\Models\EventData $eventData */
$breadcrumbs = ['breadcrumb' => 'event_datas.index'];
@endphp

@extends("$layout.app")

@push('scripts')
    <script src="{{ asset('js/business/event_data_receps/index.js') }}"></script>
@endpush

@section('title', $eventData->classLabel())

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            @include('layouts.partials.index_header', ['modelName' => $eventData->classLabel(true), 'model' => 'event_data', 'createUrl' => null, 'buttons' => [
                [
                    'route' => route('event_data_receps.export_excel'),
                    'text'  => __('Export excel'),
                    'icon'  => 'fa fa-file-excel',
                    'btnClass' => 'btn-brand btn-export-excel d-none d-sm-block',
                    'isLink' => true,
                    'canDo' => true,
                ],
            ]])
            <div class="m-portlet__body">
                @include('layouts.partials.search', ['form' => view('business.event_datas._search')->with('eventData', $eventData)])
                <table class="table table-borderless table-hover nowrap" id="table_event_datas" width="100%">
                    <thead>
                        <tr>
                            {{--<th width="5%"><label class="m-checkbox m-checkbox--all m-checkbox--solid m-checkbox--brand"><input type="checkbox"><span></span></label></th>--}}
                            <th>{{ 'Giờ' }}</th>
                            <th>{{ 'Ngày' }}</th>
                            <th>{{ $eventData->label('name') }}</th>
                            <th>{{ $eventData->label('phone') }}</th>
                            <th>{{ $eventData->label('code') }}</th>
                            <th>{{ $eventData->label('TO') }}</th>
                            <th>{{ $eventData->label('REP') }}</th>
                            <th>{{ $eventData->label('CS') }}</th>
                            <th>{{ 'Trạng thái queue' }}</th>
                            <th>{{ $eventData->label('state') }}</th>
                            <th>@lang('Actions')</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection