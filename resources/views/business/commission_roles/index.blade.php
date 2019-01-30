@php /** @var \App\Models\User $user */
$breadcrumbs = ['breadcrumb' => 'users.index', 'label' => 'Commission'];
@endphp@extends("$layout.app")

@push('scripts')
    <script src="{{ asset('js/business/commission_roles/index.js') }}"></script>
@endpush

@section('title', 'Commission')

@section('content')
    <div class="m-content">
        <div class="m-portlet">
{{--            @include('layouts.partials.index_header', ['modelName' => 'Commission', 'model' => 'user', 'createUrl' => '', 'editUrl' => ''])--}}
            <div class="m-portlet__body">
                {{--@include('layouts.partials.search', ['form' => view('report.commission_details._search', ['user' => $user])])--}}
                <table class="table table-borderless table-hover nowrap" id="table_commission_role_report"  width="100%">
                    <thead>
                    <tr>
                        <th>Chức vụ</th>
                        <th>Hình thức</th>
                        <th>Hạn mức</th>
                        <th>% Commisions</th>
                        <th>% Commision Bonus</th>
                        <th>Deal completed</th>
                        <th>@lang('Actions')</th>
                    </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

