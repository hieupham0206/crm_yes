@php /** @var \App\Models\User $user */
$breadcrumbs = ['breadcrumb' => 'users.index', 'label' => 'Commission nhân viên'];
@endphp@extends("$layout.app")

@push('scripts')
    <script src="{{ asset('js/report/commission_users/index.js') }}"></script>
@endpush

@section('title', 'Commission nhân viên')

@section('content')
    <div class="m-content">
        <div class="m-portlet">
{{--            @include('layouts.partials.index_header', ['modelName' => $user->classLabel(true), 'model' => 'user', 'createUrl' => '', 'editUrl' => ''])--}}
            <div class="m-portlet__body">
                @include('layouts.partials.search', ['form' => view('report.commission_users._search')])
                <table class="table table-borderless table-hover nowrap" id="table_commission_user_report"  width="100%">
                    <thead>
                    <tr>
                        <th>Chức vụ</th>
                        <th>Tên nhân viên</th>
                        <th>Tổng số hợp đồng</th>
                        <th>Tele</th>
                        <th>Private</th>
                        <th>Ambassador</th>
                        <th>Total commission</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

