@php /** @var \App\Models\%%modelName%% $appointment */
$breadcrumbs = ['breadcrumb' => 'appointments.index'];
@endphp@extends("$layout.app")

@push('scripts')
    <script src="{{ asset('js/business/appointments/index.js') }}"></script>
@endpush

@section('title', $appointment->classLabel())

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            {{--[--}}
            {{--'route' => route('appointments.form_import'),--}}
            {{--'text'  => __('Import'),--}}
            {{--'icon'  => 'fa fa-upload',--}}
            {{--'btnClass' => 'btn-brand btn-form-import d-none d-sm-block',--}}
            {{--'canDo' => can('create-appointment'),--}}
            {{--],--}}
            @include('layouts.partials.index_header', ['modelName' => $appointment->classLabel(true), 'model' => 'appointment', 'createUrl' => '', 'buttons' => [
                [
                    'route' => route('appointments.export_excel'),
                    'text'  => __('Export excel'),
                    'icon'  => 'fa fa-file-excel',
                    'btnClass' => 'btn-brand btn-export-excel d-none d-sm-block',
                    'isLink' => true,
                    'canDo' => true,
                ],

            ]])
            <div class="m-portlet__body">
                @include('layouts.partials.search', ['form' => view('business.appointments._search')->with('appointment', $appointment)])
                <table class="table table-borderless table-hover nowrap" id="table_appointments" width="100%">
                    <thead>
                    <tr>
                        {{--<th width="5%"><label class="m-checkbox m-checkbox--all m-checkbox--solid m-checkbox--brand"><input type="checkbox"><span></span></label></th>--}}
                        <th>{{ 'Giờ' }}</th>
                        <th>{{ 'Ngày' }}</th>
                        <th>{{ $appointment->label('user') }}</th>
                        <th>{{ $appointment->label('lead_name') }}</th>

                        <th>{{ $appointment->label('spouse_name') }}</th>
                        <th>{{ $appointment->label('phone') }}</th>
                        <th>{{ $appointment->label('spouse_phone') }}</th>
                        <th>{{ $appointment->label('code') }}</th>
                        <th>{{ 'Ngày hẹn' }}</th>
                        <th>{{ 'Giờ hẹn' }}</th>
                        {{--<th>{{ $appointment->label('call_time') }}</th>--}}
                        <th>{{ $appointment->label('state') }}</th>
                        <th>{{ $appointment->label('note') }}</th>

                        <th>@lang('Actions')</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection