@php /** @var \App\Models\Appointment $appointment */ @endphp
<form id="appointments_form" class="m-form m-form--label-align-right m-form--group-seperator-dashed m-form--state" method="post" action="{{ $action }}">
    @csrf
    @isset($method)
        @method('put')
    @endisset
    <div class="m-portlet__body">
        <div class="form-group m-form__group row">
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('lead_id') ? 'has-danger' : ''}}">
                <label for="select_lead">{{ $appointment->label('lead') }}</label>
                {{--<input class="form-control" name="lead_id" type="text" id="txt_lead_id" value="{{ $callback->lead_id ?? old('lead_id')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">--}}
                <select name="lead_id" id="select_lead" class="form-control select2-ajax" data-url="{{ route('leads.list') }}" required>
                    <option></option>
                    @if ($appointment->lead_id)
                        <option value="{{ $appointment->lead_id }}" selected>{{ $appointment->lead->name }}</option>
                    @endif
                </select>
                <span class="m-form__help"></span>
                {!! $errors->first('lead_id', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('user_id') ? 'has-danger' : ''}}">
                <label for="select_user">{{ $appointment->label('user') }}</label>
                <select name="user_id" id="select_user" class="form-control select2-ajax" data-url="{{ route('users.list') }}" required>
                    <option></option>
                    @if ($appointment->user_id)
                        <option value="{{ $appointment->user_id }}" selected>{{ $appointment->user->name }}</option>
                    @endif
                </select>
                <span class="m-form__help"></span>
                {!! $errors->first('user_id', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('appointment_datetime') ? 'has-danger' : ''}}">
                <label for="txt_callback_datetime">{{ $appointment->label('appointment_datetime') }}</label>
                <input class="form-control text-datepicker" name="appointment_datetime" type="text" id="txt_appointment_datetime" value="{{ $appointment->appointment_datetime ?? old('appointment_datetime')}}" required placeholder="{{ __('Enter value') }}"
                       autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('appointment_datetime', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_name') ? 'has-danger' : ''}}">
                <label for="txt_callback_datetime">{{ $appointment->label('spouse_name') }}</label>
                <input class="form-control" name="spouse_name" type="text" id="txt_spouse_name" value="{{ $appointment->spouse_name ?? old('spouse_name')}}" required placeholder="{{ __('Enter value') }}"
                       autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('spouse_name', '<div class="form-control-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="form-group m-form__group row">
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_phone') ? 'has-danger' : ''}}">
                <label for="txt_callback_datetime">{{ $appointment->label('spouse_phone') }}</label>
                <input class="form-control" name="spouse_phone" type="text" id="txt_spouse_phone" value="{{ $appointment->spouse_phone ?? old('spouse_phone')}}" required placeholder="{{ __('Enter value') }}"
                       autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('spouse_phone', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('comment') ? 'has-danger' : ''}}">
                <label for="textarea_comment">{{ $lead->label('comment') }}</label>
                <textarea class="form-control" rows="5" name="comment" id="textarea_comment">{{ $lead->comment ?? ''}}</textarea>
                <span class="m-form__help"></span>
                {!! $errors->first('comment', '<div class="form-control-feedback">:message</div>') !!}
            </div>
        </div>
    </div>
    <div class="m-portlet__foot m-portlet__foot--fit m-portlet__foot-no-border">
        <div class="m-form__actions m-form__actions--right">
            <button class="btn btn-brand m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-save"></i><span>@lang('Save')</span></span></button>
            <a href="{{ route('appointments.index') }}" class="btn btn-secondary m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-ban"></i><span>@lang('Cancel')</span></span></a>
        </div>
    </div>
</form>