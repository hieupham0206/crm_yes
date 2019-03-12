@php /** @var \App\Models\HistoryCall $historyCall */
$breadcrumbs = ['breadcrumb' => 'history_calls.index', 'label' => __('History call')];
@endphp

@extends("$layout.app")

@push('scripts')
    <script src="{{ asset('js/business/history_calls/index.js') }}"></script>
@endpush

@section('title', $historyCall->classLabel())

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            @include('layouts.partials.index_header', ['modelName' => $historyCall->classLabel(true), 'model' => 'history_call', 'createUrl' => '', 'buttons' => [
                [
                    'route' => route('history_calls.export_excel'),
                    'text'  => __('Export excel'),
                    'icon'  => 'fa fa-file-excel',
                    'btnClass' => 'btn-brand btn-export-excel d-none d-sm-block',
                    'isLink' => true,
                    'canDo' => true,
                ],
            ]])
            <div class="m-portlet__body">
                @include('layouts.partials.search', ['form' => view('business.history_calls._search')->with('historyCall', $historyCall)->with('leadStates', $leadStates)])
                <table class="table table-borderless table-hover nowrap" id="table_history_calls" width="100%">
                    <thead>
                        <tr>
                            <th width="5%"><label class="m-checkbox m-checkbox--all m-checkbox--solid m-checkbox--brand"><input type="checkbox"><span></span></label></th>
                            <th>{{ $historyCall->label('user') }}</th>
                            {{--<th>{{ $historyCall->label('lead') }}</th>--}}
                            <th>SDT</th>
                            <th>{{ $historyCall->label('start') }}</th>
                            <th>{{ $historyCall->label('call_status') }}</th>
                            <th>{{ $historyCall->label('time_of_call') }}</th>
                            <th>{{ $historyCall->label('comment') }}</th>
                            <th>{{ $historyCall->label('type') }}</th>
                            {{--<th>@lang('Actions')</th>--}}
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection