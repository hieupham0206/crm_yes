@php /** @var \App\Models\Callback $callback */ @endphp
<form id="callbacks_form" class="m-form m-form--label-align-right m-form--group-seperator-dashed m-form--state" method="post" action="{{ $action }}">
    @csrf
    @isset($method)
        @method('put')
    @endisset
    <div class="m-portlet__body">
        <div class="form-group m-form__group row">
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('lead_id') ? 'has-danger' : ''}}">
                <label for="select_lead">{{ $callback->label('lead') }}</label>
                {{--<input class="form-control" name="lead_id" type="text" id="txt_lead_id" value="{{ $callback->lead_id ?? old('lead_id')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">--}}
                <select name="lead_id" id="select_lead" class="form-control select2-ajax" data-url="{{ route('leads.list') }}" required>
                    <option></option>
                    @if ($callback->lead_id)
                        <option value="{{ $callback->lead_id }}" selected>{{ $callback->lead->name }}</option>
                    @endif
                </select>
                <span class="m-form__help"></span>
                {!! $errors->first('lead_id', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('user_id') ? 'has-danger' : ''}}">
                <label for="select_user">{{ $callback->label('user') }}</label>
                <select name="user_id" id="select_user" class="form-control select2-ajax" data-url="{{ route('users.list') }}" required>
                    <option></option>
                    @if ($callback->user_id)
                        <option value="{{ $callback->user_id }}" selected>{{ $callback->user->name }}</option>
                    @endif
                </select>
                <span class="m-form__help"></span>
                {!! $errors->first('user_id', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('callback_datetime') ? 'has-danger' : ''}}">
                <label for="txt_callback_datetime">{{ $callback->label('callback_datetime') }}</label>
                <input class="form-control text-datepicker" name="callback_datetime" type="text" id="txt_callback_datetime" value="{{ $callback->callback_datetime ?? old('callback_datetime')}}" required placeholder="{{ __('Enter value') }}"
                       autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('callback_datetime', '<div class="form-control-feedback">:message</div>') !!}
            </div>
        </div>
    </div>
    <div class="m-portlet__foot m-portlet__foot--fit m-portlet__foot-no-border">
        <div class="m-form__actions m-form__actions--right">
            <button class="btn btn-brand m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-save"></i><span>@lang('Save')</span></span></button>
            <a href="{{ route('callbacks.index') }}" class="btn btn-secondary m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-ban"></i><span>@lang('Cancel')</span></span></a>
        </div>
    </div>
</form>