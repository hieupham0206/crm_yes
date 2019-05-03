@php /** @var \App\Models\lead $lead */
$breadcrumbs = ['breadcrumb' => 'leads.index'];
@endphp@extends("$layout.app")

@push('scripts')
    <script src="{{ asset('js/business/leads/index.js') }}"></script>
@endpush

@section('title', $lead->classLabel())

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            @include('layouts.partials.index_header', ['modelName' => $lead->classLabel(true), 'model' => 'lead', 'createUrl' => route('leads.create'), 'buttons' => [
                [
                    'route' => route('leads.form_import'),
                    'text'  => __('Import'),
                    'icon'  => 'fa fa-upload',
                    'btnClass' => 'btn-brand btn-form-import d-none d-sm-block',
                    'canDo' => can('create-lead'),
                ],
                [
                    'route' => route('leads.export_excel'),
                    'text'  => __('Export excel'),
                    'icon'  => 'fa fa-file-excel',
                    'btnClass' => 'btn-brand btn-export-excel d-none d-sm-block',
                    'isLink' => true,
                    'canDo' => true,
                ],
            ]])
            <div class="m-portlet__body">
                @include('layouts.partials.search', ['form' => view('business.leads._search')->with('lead', $lead)])
                <table class="table table-borderless table-hover nowrap" id="table_leads" width="100%">
                    <thead>
                    <tr>
                        {{--<th width="5%"><label class="m-checkbox m-checkbox--all m-checkbox--solid m-checkbox--brand"><input type="checkbox"><span></span></label></th>--}}
                        <th>STT</th>
                        <th>{{ $lead->label('name') }}</th>
                        <th>{{ $lead->label('title') }}</th>
                        <th>{{ $lead->label('email') }}</th>
                        {{--<th>{{ $lead->label('gender') }}</th>--}}
                        <th>{{ $lead->label('birthday') }}</th>
                        <th>{{ $lead->label('province') }}</th>
                        <th>{{ $lead->label('phone') }}</th>
                        <th>{{ $lead->label('state') }}</th>
                        <th>@lang('Actions')</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection