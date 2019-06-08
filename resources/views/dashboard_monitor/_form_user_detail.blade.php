<form id="import_leads_form" class="m-form m-form--state" method="post" action="{{ route('leads.import') }}" enctype="multipart/form-data">
    <div class="modal-header">
        {{--<h5 class="modal-title">@lang('Import')</h5>--}}
        {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
            {{--<span aria-hidden="true">&times;</span>--}}
        {{--</button>--}}
    </div>
    <div class="modal-body">
        @csrf
        <div class="m-portlet__body">
            <div class="col-md-12 border-bottom">
                <div class="m-widget24 p-2">
                    <div class="m-widget24__item">
                        <input type="hidden" id="txt_form_modal_bg" value="{{ $user->getBgClassOnDashboard()[1] }}">
                        @php
                            $callCache = $user->getCallCache()
                        @endphp
                        <h3 class="text-center">
                            <a href="javascript:void(0)" class="m-link">{!! $user->name ?: $user->username  !!}</a>
                        </h3>
                        <h4 class="m-widget24__title">
                            Log time: <span class="span-login-time-modal" data-time-in-second="{{ $user->login_time_in_second }}" data-is-online="{{ $user->isOnline() ? 'true' : 'false' }}">
                                {{ $user->isOnline() ? $user->login_time_string : '00:00:00' }}
                            </span>
                        </h4>
                        <h4 class="m-widget24__title float-right">
                            Total call: {{ $callCache['totalCall'] }}
                        </h4>
                        <br>
                        <span class="m-widget24__stats m--font-danger float-none" style="margin-left: 1.8rem">
                        {!! $user->current_state !!}
                        </span>
                        <h4 class="m-widget24__title float-right">
                            Appointment: {{ $user->appointments_count }}
                        </h4>
                        <h4 class="m-widget24__title float-right">
                            Private: {{ $user->privates->count() }}
                        </h4>
                        <h4 class="m-widget24__title float-right">
                            Datas: {{ $user->private_stills->count() }}
                        </h4>
                        <div class="m--space-10"></div>
                        <br>
                        @if ($user->current_state == 'In call')
                            <h4 class="m-widget24__title">
                                Type call: {{ $callCache['typeCall'] }}
                            </h4>
                            <br>
                            <h4 class="m-widget24__title">
                                Khách: {{ $callCache['leadName'] }}
                            </h4>
                            <br>
                            <h4 class="m-widget24__title">
                                Thời gian: <span class="span-call-time" data-call-time-in-second="{{ now()->diffAsCarbonInterval($callCache['callAt'])->totalSeconds }}"></span>
                            </h4>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary m-btn--custom m-btn--icon" data-dismiss="modal">
            <span>
                <i class="fa fa-ban"></i>
                <span>@lang('Close')</span>
            </span>
        </button>
    </div>
</form>