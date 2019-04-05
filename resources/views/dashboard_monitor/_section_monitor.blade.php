@foreach ($users as $user)
    <div class="col-2 col-md-1 border-bottom">
        <div class="m-widget24-bg m-widget24 p-2 {{ $user->getBgClassOnDashboard()[0] }}">
            <div class="m-widget24__item">
                @php
                    $callCache = $user->getCallCache()
                @endphp
                <h4 class="text-center">
                    <a href="javascript:void(0)" class="m-link link-form-detail" data-url="{{ route('monitor_sale.form_detail', $user) }}">{!! $user->getShortName()!!} ({{ $user->private_stills->count() }})</a>
                </h4>
                {{--<h5 class="m-widget24__title text-center">--}}
                    {{--<span class="span-login-time" data-time-in-second="{{ $user->login_time_in_second }}" data-is-online="{{ $user->isOnline() ? 'true' : 'false' }}">--}}
                        {{--{{ $user->isOnline() ? $user->login_time_string : '00:00:00' }}--}}
                    {{--</span>--}}
                {{--</h5>--}}
                <h5 class="m-widget24__title" style="width: 100%">
                    Call: {{ $callCache['totalCall'] }}
                </h5>
{{--                <h5 class="m-widget24__title" style="width: 100%">--}}
{{--                    {!! $user->getShortName()!!}(still): {{ $user->private_stills->count() }}--}}
{{--                </h5>--}}
                <br>
                {{--<span class="m-widget24__stats m--font-danger float-none" style="margin-left: 1.8rem">--}}
                    {{--{!! $user->current_state !!}--}}
                {{--</span>--}}
                {{--<h5 class="m-widget24__title appointment-count">--}}
                    {{--App: {{ $user->appointments_count }}--}}
                {{--</h5>--}}
                {{--<div class="m--space-10"></div>--}}
                {{--<br>--}}
                {{--@if ($user->current_state == 'In call')--}}
                    {{--<h4 class="m-widget24__title">--}}
                        {{--Type call: {{ $callCache['typeCall'] }}--}}
                    {{--</h4>--}}
                    {{--<br>--}}
                    {{--<h4 class="m-widget24__title">--}}
                        {{--Khách: {{ $callCache['leadName'] }}--}}
                    {{--</h4>--}}
                    {{--<br>--}}
                    {{--<h4 class="m-widget24__title">--}}
                        {{--Thời gian: <span class="span-call-time" data-call-time-in-second="{{ now()->diffAsCarbonInterval($callCache['callAt'])->totalSeconds }}"></span>--}}
                    {{--</h4>--}}
                {{--@endif--}}
            </div>
        </div>
    </div>
@endforeach