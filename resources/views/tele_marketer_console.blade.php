@extends("$layout.app")@section('title', __('Telemaketer Console'))

@push('scripts')
    <script src="{{ asset('js/tele_console.js') }}"></script>
@endpush

@section('content')
    <audio id="audio_alert" controls preload="none" style="display: none">
        <source src="{{ asset('files/alert.mp3') }}" type="audio/mpeg">
    </audio>
    <div class="m-content my-3">
        <input type="hidden" id="txt_user_id" value="{{ auth()->id() }}">
        <input type="hidden" id="txt_lead_id" value="{{ optional($lead)->id }}">
        <input type="hidden" id="txt_is_checked_in" value="{{ $isCheckedIn }}">
        <div class="row">
            <div class="col-lg-12">
                <div class="m-portlet" id="break_section">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <h3 class="m-portlet__head-text">Customer No: <span id="span_customer_no">0</span></h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <label for="" class="mr-3 label-span-time" style="display: {{ $isCheckedIn ? 'block' : 'none' }}">Login time: <span id="span_login_time" class="span-time" data-diff-login-time="{{ $diffLoginString }}"></span></label>
                            <label for="" class="mr-3 label-span-time" style="display: {{ $isCheckedIn ? 'block' : 'none' }}">Pause time:
                                <span id="span_pause_time" class="span-time" data-diff-break-time="{{ $diffBreakString }}" data-max-break-time="{{ $maxBreakTime }}" data-start-break-value="{{ $startBreakValue }}">00:00:00</span>
                            </label>
                            <button class="btn btn-brand btn-sm m-btn m-btn--icon m-btn--custom mr-2" id="btn_pause" style="display: {{ $isCheckedIn ? 'block' : 'none' }}">
                                <span><i class="fa fa-pause"></i><span>@lang('Pause')</span></span>
                            </button>
                            @if ($isCheckedIn)
                                <button class="btn btn-brand btn-sm m-btn m-btn--icon m-btn--custom mr-2" id="btn_check_in" style="display: none">
                                    <span><i class="fa fa-check"></i><span>@lang('Check in')</span></span>
                                </button>
                                <button class="btn btn-brand btn-sm m-btn m-btn--icon m-btn--custom mr-2" id="btn_check_out">
                                    <span><i class="fa fa-sign-out-alt"></i><span>@lang('Check out')</span></span>
                                </button>

                                @if ($isLoadPrivateOnly)
                                    <button class="btn btn-brand btn-sm m-btn m-btn--icon m-btn--custom mr-2" id="btn_load_private" style="display: none">
                                        <span><i class="fa fa-check"></i><span>@lang('Load private')</span></span>
                                    </button>
                                    <button class="btn btn-brand btn-sm m-btn m-btn--icon m-btn--custom mr-2" id="btn_not_load_private">
                                        <span><i class="fa fa-sign-out-alt"></i><span>@lang('Load public')</span></span>
                                    </button>
                                @else
                                    <button class="btn btn-brand btn-sm m-btn m-btn--icon m-btn--custom mr-2" id="btn_load_private">
                                        <span><i class="fa fa-check"></i><span>@lang('Load private')</span></span>
                                    </button>
                                    <button class="btn btn-brand btn-sm m-btn m-btn--icon m-btn--custom mr-2" id="btn_not_load_private" style="display: none">
                                        <span><i class="fa fa-sign-out-alt"></i><span>@lang('Load public')</span></span>
                                    </button>
                                @endif
                            @else
                                <button class="btn btn-brand btn-sm m-btn m-btn--icon m-btn--custom mr-2" id="btn_load_private" style="display: none">
                                    <span><i class="fa fa-check"></i><span>@lang('Load private')</span></span>
                                </button>
                                <button class="btn btn-brand btn-sm m-btn m-btn--icon m-btn--custom mr-2" id="btn_not_load_private" style="display: none">
                                    <span><i class="fa fa-sign-out-alt"></i><span>@lang('Load public')</span></span>
                                </button>
                                <button class="btn btn-brand btn-sm m-btn m-btn--icon m-btn--custom mr-2" id="btn_check_in">
                                    <span><i class="fa fa-check"></i><span>@lang('Check in')</span></span>
                                </button>
                                <button class="btn btn-brand btn-sm m-btn m-btn--icon m-btn--custom mr-2" id="btn_check_out" style="display: none">
                                    <span><i class="fa fa-sign-out-alt"></i><span>@lang('Check out')</span></span>
                                </button>
                            @endif
                            <button class="btn btn-brand btn-sm m-btn m-btn--icon m-btn--custom" id="btn_resume" data-url="{{ route('users.resume') }}" style="display: none">
                                <span><i class="fa fa-play"></i><span>@lang('Resume')</span></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @if ($lead)
                <div class="col-xl-6 col-lg-12 work-section">
                    <div class="m-portlet">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text">Customer Info</h3>
                                </div>
                            </div>
                            <div class="m-portlet__head-tools">
                                <span class="m-portlet__head-text">Type call: <span id="span_call_type">Auto</span></span>
                                <span class="m-portlet__head-text ml-3 span-auto-call-time">Time: <span id="span_call_time" class="span-time">{{ $startCallTime }}</span></span>
                            </div>
                        </div>
                        <form id="leads_form" class="m-form m-form--label-align-right m-form--state" method="post" action="{{ route('leads.update', $lead) }}">
                            <div class="m-portlet__body">
                                <input type="hidden" id="txt_appointment_id" name="appointment_id">
                                {{--<div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true" data-height="300" data-scrollbar-shown="true">--}}
                                @csrf
                                @isset($method)
                                    @method('put')
                                @endisset
                                <div class="form-group m-form__group row">
                                    <div class="col-sm-12 col-md-3 m-form__group-sub">
                                        <label for="span_lead_title">{{ $lead->label('title') }}: </label>
                                        <span id="span_lead_title">{{ $lead->title }}</span>
                                        <span class="m-form__help"></span>
                                        {!! $errors->first('title', '<div class="form-control-feedback">:message</div>') !!}
                                    </div>
                                    <div class="col-sm-12 col-md-5 m-form__group-sub">
                                        <label for="span_lead_name">{{ $lead->label('name') }}: </label>
                                        <span id="span_lead_name">{{ $lead->name }}</span>
                                    </div>
                                    <div class="col-sm-12 col-md-4 m-form__group-sub">
                                        <label for="span_lead_birthday">{{ $lead->label('birthday') }}: </label>
                                        <span id="span_lead_birthday">{{ optional($lead->birthday)->format('d-m-Y') }}</span>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <div class="col-sm-12 col-md-8 m-form__group-sub">
                                        <label for="span_lead_phone">{{ $lead->label('phone') }}: </label>
                                        <span class="font-weight-bold m--font-danger m--icon-font-size-lg4 ml-3" id="span_lead_phone">{{ $lead->phone }}</span>
                                    </div>
                                    <div class="col-sm-12 col-md-4 m-form__group-sub">
                                        <button class="btn btn-brand m-btn m-btn--icon m-btn--custom" id="btn_form_change_state" data-url="{{ route('leads.form_change_state', $lead) }}">
                                            <span><i class="fa fa-save"></i><span>@lang('New Customer')</span></span>
                                        </button>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <div class="col-md-9 m-form__group-sub">
                                        <label for="span_lead_visibility">{{ $lead->label('source') }}: </label>
                                        <span id="span_lead_visibility">{{ $lead->visibility }}</span>
                                    </div>
                                    <div class="col-md-9 m-form__group-sub">
                                        <div class="btn-group m-btn-group" role="group" aria-label="...">
                                            <input class="form-control" name="phone_out_call" id="txt_phone_out_call" value="" autocomplete="off">
                                            <button type="button" class="btn btn-info m-btn m-btn--icon m-btn--custom" id="btn_callout">
                                                <span><i class="fa fa-phone"></i><span>Call</span></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row" id="section_appointment_feature" style="display: none;">
                                    <div class="col-md-12 m-form__group-sub">
                                        <div class="btn-group m-btn-group" role="group" aria-label="...">
                                            <button type="button" class="btn btn-info m-btn m-btn--icon m-btn--custom" id="btn_resend_appointment_email">
                                                <span><i class="fa fa-envelope"></i><span>Email</span></span>
                                            </button>
                                            <button type="button" class="btn btn-info m-btn m-btn--icon m-btn--custom" id="btn_reappointment">
                                                <span><i class="fa fa-redo"></i><span>Re-App</span></span>
                                            </button>
                                            <button type="button" class="btn btn-danger m-btn m-btn--icon m-btn--custom" id="btn_cancel_appointment">
                                                <span><i class="fa fa-ban"></i><span>Cancel-App</span></span>
                                            </button>
                                            <button type="button" class="btn btn-brand m-btn m-btn--icon m-btn--custom" id="btn_appointment_confirm">
                                                <span><i class="fa fa-check"></i><span>Confirm</span></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="m-portlet m-portlet--primary m-portlet--head-solid-bg">
                            <div class="m-portlet__head">
                                <div class="m-portlet__head-caption">
                                    <div class="m-portlet__head-title">
                                        <h3 class="m-portlet__head-text">Customer History</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="m-portlet__body">
                                <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true" data-height="200" data-scrollbar-shown="true">
                                    <table class="table table-borderless table-hover" id="table_customer_history" width="100%">
                                        <thead>
                                        <tr>
                                            <th width="25%">{{ $lead->label('created_at') }}</th>
                                            <th width="25%">{{ $lead->label('state') }}</th>
                                            <th width="50%">{{ $lead->label('comment') }}</th>
                                            {{--<th>{{ __('Actions') }}</th>--}}
                                        </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text">History Call</h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <div class="m-dropdown__scrollable m-scrollable" data-scrollable="true" data-height="200" data-mobile-height="200">
                                <table class="table table-borderless table-hover" id="table_history_calls" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="20%">{{ $lead->label('name') }}</th>
                                        <th width="15%">{{ $lead->label('created_at') }}</th>
                                        <th width="20%">{{ $lead->label('state') }}</th>
                                        <th width="40%">{{ $lead->label('comment') }}</th>
                                        <th width="5%">{{ __('Actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-6 col-lg-12 work-section">
                    <div class="m-portlet m-portlet--brand m-portlet--head-solid-bg">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text">Follow List</h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true" data-height="200" data-scrollbar-shown="true">
                                <table class="table table-borderless table-hover" id="table_callback" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="5%">{{ $lead->label('phone') }}</th>
                                        <th width="25%">{{ $lead->label('name') }}</th>
                                        <th width="15%">{{ $lead->label('callback_datetime') }}</th>
                                        <th width="40%">{{ $lead->label('comment') }}</th>
                                        <th width="15%">{{ $lead->label('actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="m-portlet m-portlet--info m-portlet--head-solid-bg">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <h3 class="m-portlet__head-text">Appointment List</h3>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body">
                            <div class="m-scrollable m-scroller ps ps--active-y" data-scrollable="true" data-height="412" data-scrollbar-shown="true">
                                <table class="table table-borderless table-hover" id="table_appointment" width="100%">
                                    <thead>
                                    <tr>
                                        <th width="5%">{{ $lead->label('phone') }}</th>
                                        <th width="25%">{{ $lead->label('name') }}</th>
                                        <th width="20%">{{ $lead->label('appointment_datetime') }}</th>
                                        <th width="40%">{{ $lead->label('comment') }}</th>
                                        <th width="10%">{{ $lead->label('actions') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="modal modal-wide fade modal-scroll" id="modal_recall" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content"></div>
        </div>
    </div>

    <div class="modal modal-wide fade modal-scroll" id="modal_outcall" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content"></div>
        </div>
    </div>
@endsection