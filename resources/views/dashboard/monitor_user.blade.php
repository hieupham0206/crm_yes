@extends("$layout.app")@section('title', 'Giám sát SALE')

@push('scripts')
    <script src="{{ asset('js/monitor_sale.js') }}"></script>
@endpush

@section('content')
    <div class="m-content my-3">
        <div class="m-portlet ">
            <div class="m-portlet__body  m-portlet__body--no-padding">
                <div class="row pb-3">
                    <div class="col-md-12">
                        <button class="btn btn-sm btn-warning m-btn m-btn--custom m-btn--icon btn-filter-call" data-filter="all">
                            Tất cả
                        </button>
                        <button class="btn btn-sm btn-success m-btn m-btn--custom m-btn--icon btn-filter-call" data-filter="online">
                            Online
                        </button>
                        <button class="btn btn-sm btn-primary m-btn m-btn--custom m-btn--icon btn-filter-call" data-filter="busy">
                            Bận
                        </button>
                        <button class="btn btn-sm btn-metal m-btn m-btn--custom m-btn--icon btn-filter-call" data-filter="offline">
                            Offline
                        </button>
                        <button class="btn btn-sm btn-danger m-btn m-btn--custom m-btn--icon btn-filter-call" data-filter="overtime">
                            Quá thời gian
                        </button>
                    </div>
                </div>
                <div class="row m-row--no-padding m-row--col-separator-xl" id="section_monitor_sale">
                    @include('dashboard_monitor._section_monitor', ['users' => $users])
                </div>
            </div>
        </div>
    </div>
@endsection