@php /** @var \App\Models\User $user */
$breadcrumbs = ['breadcrumb' => 'users.index', 'label' => 'Sale KPI report'];
@endphp@extends("$layout.app")

@push('scripts')
    <script src="{{ asset('js/report/sale_kpis/index.js') }}"></script>
@endpush

@section('title', 'Daily sale report')

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            @include('layouts.partials.index_header', ['modelName' => $user->classLabel(true), 'model' => 'user', 'createUrl' => '', 'editUrl' => '', 'buttons' => [
                [
                    'route' => route('sale_kpis.export_excel'),
                    'text'  => __('Export excel'),
                    'icon'  => 'fa fa-file-excel',
                    'btnClass' => 'btn-brand btn-export-excel d-none d-sm-block',
                    'isLink' => true,
                    'canDo' => true,
                ],
            ]])
            <div class="m-portlet__body">
                @include('layouts.partials.search', ['form' => view('report.sale_kpis._search', ['user' => $user])])
                <table class="table table-borderless table-hover nowrap" id="table_sale_kpi_report" width="100%">
                    <thead>
                    <tr>
                        <th>Tên người dùng</th>
                        <th>Vai trò</th>
                        <th>Ngày</th>
                        <th>Login</th>
                        <th>Logout</th>
                        <th>Duration</th>
                        <th>Total dial</th>
                        <th>Total connection</th>
                        <th>Total no answer</th>
                        <th>Total appointment</th>
                        <th>Total show</th>
                        <th>Total tour</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

