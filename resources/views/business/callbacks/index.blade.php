@php /** @var \App\Models\Callback $callback */
$breadcrumbs = ['breadcrumb' => 'callbacks.index'];
@endphp@extends("$layout.app")

@push('scripts')
    <script src="{{ asset('js/business/callbacks/index.js') }}"></script>
@endpush

@section('title', $callback->classLabel())

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            @include('layouts.partials.index_header', ['modelName' => $callback->classLabel(true), 'model' => 'callback', 'createUrl' => '', 'buttons' => [
                [
                    'route' => route('callbacks.export_excel'),
                    'text'  => __('Export excel'),
                    'icon'  => 'fa fa-file-excel',
                    'btnClass' => 'btn-brand btn-export-excel d-none d-sm-block',
                    'isLink' => true,
                    'canDo' => true,
                ],
            ]])
            <div class="m-portlet__body">
                @include('layouts.partials.search', ['form' => view('business.callbacks._search')->with('callback', $callback)])
                <table class="table table-borderless table-hover nowrap" id="table_callbacks" width="100%">
                    <thead>
                    <tr>
                        {{--<th width="5%"><label class="m-checkbox m-checkbox--all m-checkbox--solid m-checkbox--brand"><input type="checkbox"><span></span></label></th>--}}
                        <th>{{ $callback->label('user') }}</th>
                        <th>{{ $callback->label('lead_name') }}</th>
                        <th>{{ $callback->label('phone') }}</th>
                        <th>Giờ</th>
                        <th>Ngày</th>
                        <th>{{ $callback->label('state') }}</th>
                        <th>Comment</th>
                        <th>Hành động</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection