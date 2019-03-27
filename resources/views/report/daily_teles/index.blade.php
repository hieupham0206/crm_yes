@php /** @var \App\Models\User $user */
$breadcrumbs = ['breadcrumb' => 'users.index', 'label' => 'Daily tele report'];
@endphp@extends("$layout.app")

@push('scripts')
    <script src="{{ asset('js/report/daily_teles/index.js') }}"></script>
@endpush

@section('title', 'Daily tele report')

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            @include('layouts.partials.index_header', ['modelName' => $user->classLabel(true), 'model' => 'user', 'createUrl' => '', 'editUrl' => '', 'buttons' => [
                [
                    'route' => route('daily_teles.export_excel'),
                    'text'  => __('Export excel'),
                    'icon'  => 'fa fa-file-excel',
                    'btnClass' => 'btn-brand btn-export-excel d-none d-sm-block',
                    'isLink' => true,
                    'canDo' => true,
                ],
            ]])
            <div class="m-portlet__body">
                @include('layouts.partials.search', ['form' => view('report.daily_teles._search', ['user' => $user])])
                <table class="table table-borderless table-hover nowrap" id="table_daily_tele_report"  width="100%">
                    <thead>
                    <tr>
                        <th width="5%"><label class="m-checkbox m-checkbox--all m-checkbox--solid m-checkbox--brand"><input type="checkbox"><span></span></label></th>
                        <th>{{ $user->label('name') }}</th>
                        <th>{{ $user->label('role') }}</th>
                        <th>{{ $user->label('queue') }}</th>
                        <th>{{ $user->label('not_queue') }}</th>
                        <th>{{ $user->label('no_rep') }}</th>
                        <th>{{ $user->label('overflow') }}</th>

                        <th>{{ $user->label('3pm_event') }}</th>
                        <th>{{ $user->label('re_app') }}</th>
                        <th>{{ $user->label('total_app') }}</th>
                        <th>{{ $user->label('rate_app') }}</th>

                        <th>{{ $user->label('total_show') }}</th>
                        <th>{{ $user->label('deal') }}</th>
                        <th>{{ $user->label('rate_deal') }}</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

