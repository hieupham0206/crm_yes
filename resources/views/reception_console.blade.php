@extends("$layout.app")@section('title', __('Reception'))

@push('scripts')
    <script src="{{ asset('js/reception.js') }}"></script>
@endpush
@push('styles')
    <style>
        body {
            font-size: 11px;
        }
    </style>
@endpush

@section('content')
    <div class="m-content my-3">
        <input type="hidden" id="txt_user_id" value="{{ auth()->id() }}">
        <div class="row">
            <div class="col-lg-12">
                <div class="m-portlet" id="break_section">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            {{--<div class="m-portlet__head-title">--}}
                            {{--<h3 class="m-portlet__head-text">Customer No: <span id="span_customer_no">0</span></h3>--}}

                            {{--<label for="txt_voucher_code">{{ $lead->label('code') }}</label>--}}
                            <input type="text" name="voucher_code" id="txt_voucher_code" class="form-control m-input ml-3" placeholder="Code" autocomplete="off">
                            {{--<label for="txt_phone">{{ $lead->label('phone') }}</label>--}}
                            <input type="text" name="phone" id="txt_phone" class="form-control m-input ml-4" placeholder="Số điện thoại" autocomplete="off">
                            {{--</div>--}}
                            <button class="btn btn-brand btn-sm m-btn m-btn--icon m-btn--custom ml-3" id="btn_search">
                                <span> <i class="fa fa-search"></i> <span>@lang('Search')</span> </span>
                            </button>
                            <button class="btn btn-brand btn-sm m-btn m-btn--icon m-btn--custom ml-3" id="btn_new_lead">
                                <span> <i class="fa fa-plus"></i> <span>@lang('New Customer')</span> </span>
                            </button>
                        </div>
                        {{--<div class="m-portlet__head-tools">--}}
                        {{--<button class="btn btn-brand btn-sm m-btn m-btn--icon m-btn--custom mr-2" id="btn_pause" data-url="{{ route('users.form_break') }}">--}}
                        {{--<span><i class="fa fa-pause"></i><span>@lang('Pause')</span></span>--}}
                        {{--</button>--}}
                        {{--<button class="btn btn-brand btn-sm m-btn m-btn--icon m-btn--custom" id="btn_resume" data-url="{{ route('users.resume') }}" style="display: none">--}}
                        {{--<span><i class="fa fa-play"></i><span>@lang('Resume')</span></span>--}}
                        {{--</button>--}}
                        {{--</div>--}}
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12">
                <div class="m-portlet m-portlet--info m-portlet--head-solid-bg">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">Appointment List</h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true" data-height="300" data-scrollbar-shown="true">
                            <table class="table table-borderless table-hover nowrap" id="table_appointment" width="100%">
                                <thead>
                                <tr>
                                    <th>{{ $lead->label('appointment_datetime') }}</th>
                                    <th>{{ $lead->label('title') }}</th>
                                    <th>{{ $lead->label('name') }}</th>
                                    <th>{{ $lead->label('phone') }}</th>
                                    <th>{{ $lead->label('code') }}</th>
                                    <th>Người hẹn</th>
                                    <th>{{ $lead->label('comment') }}</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="m-portlet m-portlet--primary m-portlet--head-solid-bg">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">Event Data</h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true" data-height="300" data-scrollbar-shown="true">
                            <table class="table table-borderless table-hover nowrap" id="table_event_data" width="100%">
                                <thead>
                                <tr>
                                    <th>{{ $lead->label('date') }}</th>
                                    <th>{{ $lead->label('title') }}</th>
                                    <th>{{ $lead->label('name') }}</th>
                                    <th>{{ $lead->label('phone') }}</th>
                                    <th>{{ $lead->label('code') }}</th>
                                    <th>{{ $lead->label('note') }}</th>
                                    <th>{{ $lead->label('TO') }}</th>
                                    <th>{{ $lead->label('REP') }}</th>
                                    {{--<th>@lang('Actions')</th>--}}
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-12">
                <div class="m-portlet ">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">Customer Info</h3>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true" data-height="300" data-scrollbar-shown="true">
                            <form id="leads_form" class="m-form m-form--label-align-right m-form--state form-lead-reception" method="post">
                                @csrf
                                @method('put')
                                <div class="m-portlet__body">
                                    <input type="hidden" name="lead_id" id="txt_lead_id">
                                    <input type="hidden" name="appointment_id" id="txt_appointment_id">
                                    @csrf
                                    @method('put')
                                    <div class="form-group m-form__group row">
                                        <div class="col-sm-12 col-md-4 m-form__group-sub">
                                            <label for="txt_lead_title">{{ $lead->label('title') }}</label>
                                            {{--<span id="span_lead_title"></span>--}}
                                            <input type="text" name="title" id="txt_lead_title">
                                        </div>
                                        <div class="col-sm-12 col-md-4 m-form__group-sub">
                                            <label for="txt_lead_name">{{ $lead->label('name') }}</label>
                                            {{--<span id="span_lead_name"></span>--}}
                                            <input type="text" name="name" id="txt_lead_name">
                                        </div>
                                        <div class="col-sm-12 col-md-4 m-form__group-sub">
                                            <label for="span_lead_phone">{{ $lead->label('phone') }}</label>
                                            <span id="span_lead_phone"></span>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <div class="col-sm-12 col-md-8 m-form__group-sub">
                                            <label for="txt_appointment_datetime">{{ $lead->label('datetime') }}</label>
                                            {{--<span id="span_appointment_datetime"></span>--}}
                                            <input type="text" name="appointment_datetime" class="text-datetimepicker" id="txt_appointment_datetime" autocomplete="off">
                                        </div>
                                        <div class="col-sm-12 col-md-4 m-form__group-sub">
                                            <label for="span_tele_marketer">Người hẹn</label>
                                            <span id="span_tele_marketer"></span>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <div class="col-sm-12 col-md-6 m-form__group-sub">
                                            <label for="txt_spouse_name">{{ $lead->label('spouse_name') }}</label>
                                            {{--<span id="span_spouse_name"></span>--}}
                                            <input type="text" name="spouse_name" id="txt_spouse_name">
                                        </div>
                                        <div class="col-sm-12 col-md-6 m-form__group-sub">
                                            <label for="txt_spouse_phone">{{ $lead->label('spouse_phone') }}</label>
                                            {{--<span id="span_spouse_phone"></span>--}}
                                            <input type="text" name="spouse_phone" id="txt_spouse_phone">
                                        </div>
                                    </div>
                                    {{--<div class="form-group m-form__group row">--}}
                                    {{--<div class="col-sm-12 col-md-8 m-form__group-sub">--}}
                                    {{--<button type="button" class="btn btn-success m-btn m-btn--icon m-btn--custom" id="btn_show_up" disabled>--}}
                                    {{--<span><i class="fa fa-check"></i><span>@lang('Show up')</span></span>--}}
                                    {{--</button>--}}
                                    {{--<button type="button" class="btn btn-danger m-btn m-btn--icon m-btn--custom" id="btn_not_show_up" data-url="{{ route('leads.form_change_state', $lead) }}" disabled>--}}
                                    {{--<span><i class="fa fa-ban"></i><span>@lang('Not')</span></span>--}}
                                    {{--</button>--}}
                                    {{--</div>--}}
                                    {{--</div>--}}
                                    <div class="form-group m-form__group row" id="queue_section">
                                        <div class="col-sm-12 col-md-12 m-form__group-sub">
                                            <button type="button" class="btn btn-sm btn-success m-btn m-btn--icon m-btn--custom" id="btn_update_lead_info">
                                                <span><i class="fa fa-save"></i><span>Cập nhật</span></span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info m-btn m-btn--icon m-btn--custom" id="btn_re_appointment">
                                                <span><i class="fa fa-redo"></i><span>Re-App</span></span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-success m-btn m-btn--icon m-btn--custom" id="btn_queue">
                                                <span><i class="fa fa-check"></i><span>@lang('Queue')</span></span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger m-btn m-btn--icon m-btn--custom" id="btn_not_queue">
                                                <span><i class="fa fa-ban"></i><span>@lang('Not queue')</span></span>
                                            </button>
                                            <button type="button" id="btn_busy" data-state="{{ \App\Enums\EventDataState::BUSY }}" data-message="" title="Busy" data-tile="" data-org-title="Hủy deal khách hàng " data-url="" data-lead-name=""
                                                    class="btn btn-sm btn-success btn-change-event-status m-btn m-btn--icon m-btn--icon-only m-btn--pill">
                                                <i class="fa fa-street-view"></i>
                                            </button>
                                            <button type="button" id="btn_overflow" data-state="{{ \App\Enums\EventDataState::OVERFLOW }}" data-message="" title="Overflow" data-tile="" data-org-title="Hủy deal khách hàng " data-url="" data-lead-name=""
                                                    class="btn btn-sm btn-danger btn-change-event-status m-btn m-btn--icon m-btn--icon-only m-btn--pill">
                                                <i class="fa fa-ban"></i>
                                            </button>
                                            <button type="button" id="btn_deal" data-state="{{ \App\Enums\EventDataState::DEAL }}" data-message="" title="Deal" data-tile="" data-org-title="Chốt deal khách hàng " data-url="" data-lead-name=""
                                                    class="btn btn-sm btn-success btn-change-event-status m-btn m-btn--icon m-btn--icon-only m-btn--pill">
                                                <i class="fa fa-check"></i>
                                            </button>
                                            <button type="button" id="btn_not_deal" data-state="{{ \App\Enums\EventDataState::NOT_DEAL }}" data-message="" title="Not deal" data-tile="" data-org-title="Hủy deal khách hàng " data-url="" data-lead-name=""
                                                    class="btn btn-sm btn-danger btn-change-event-status m-btn m-btn--icon m-btn--icon-only m-btn--pill">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="m-portlet" id="event_data_section" style="display: none">
                    <div class="m-portlet__body">
                        <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true" data-height="355" data-scrollbar-shown="true">
                            <form id="event_data_form" class="m-form m-form--label-align-right m-form--state" method="post">
                                <div class="m-portlet__body">
                                    {{--<div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true" data-height="300" data-scrollbar-shown="true">--}}
                                    @csrf
                                    @method('put')
                                    <div class="form-group m-form__group row">
                                        <input type="hidden" name="id" id="txt_event_data_id">
                                        <div class="col-sm-12 col-md-4 m-form__group-sub">
                                            <label for="select_rep">{{ $lead->label('REP') }}</label>
                                            <select name="rep_id" id="select_rep">
                                                <option></option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-4 m-form__group-sub">
                                            <label for="select_to">{{ $lead->label('TO') }}</label>
                                            <select name="to_id" id="select_to">
                                                <option></option>
                                            </select>
                                        </div>
                                        <div class="col-sm-12 col-md-4 m-form__group-sub">
                                            <label for="select_cs">{{ $lead->label('CS') }}</label>
                                            <select name="cs_id" id="select_cs">
                                                <option></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <div class="col-sm-12 col-md-6 m-form__group-sub">
                                            <label for="txt_event_data_code">{{ $lead->label('Voucher') }}</label>
                                            <input class="form-control" name="code" type="text" id="txt_event_data_code" value="" placeholder="{{ __('Enter value') }}" disabled>
                                        </div>
                                        <div class="col-sm-12 col-md-6 m-form__group-sub">
                                            <label for="txt_note">{{ $lead->label('Note') }}</label>
                                            <textarea class="form-control" name="note" row="4" id="txt_note" placeholder="{{ __('Enter value') }}" disabled></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <div class="col-sm-12 col-md-6 m-form__group-sub">
                                            <label for="span_appointment_queue">Is Queue</label>
                                            <span id="span_appointment_queue"></span>
                                        </div>
                                        <div class="col-sm-12 col-md-6 m-form__group-sub">
                                            <label for="span_event_data_status">Event data status</label>
                                            <span id="span_event_data_status"></span>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <div class="col-sm-12 col-md-6 m-form__group-sub">
                                            <label class="m-checkbox">
                                                <input type="checkbox" name="hot_bonus" value="1"> Hot Bonus
                                                <span></span>
                                            </label>
                                        </div>
                                        <div class="col-sm-12 col-md-6 m-form__group-sub">
                                            <button class="btn btn-success m-btn m-btn--icon m-btn--custom" id="btn_change_to_event_data">
                                                <span><i class="fa fa-check"></i><span>@lang('OK')</span></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection