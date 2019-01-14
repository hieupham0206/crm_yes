@php /** @var \App\Models\HistoryCall $historyCall */ @endphp

<form id="history_calls_form" class="m-form m-form--label-align-right m-form--group-seperator-dashed m-form--state" method="post" action="{{ $action }}">
    @csrf
    @isset($method)
        @method('put')
    @endisset
    <div class="m-portlet__body">
        <div class="form-group m-form__group row">
			<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('user_id') ? 'has-danger' : ''}}">
    <label for="txt_user_id">{{ $historyCall->label('user_id') }}</label>
    <input class="form-control" name="user_id" type="text" id="txt_user_id" value="{{ $historyCall->user_id ?? old('user_id')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
<span class="m-form__help"></span>
    {!! $errors->first('user_id', '<div class="form-control-feedback">:message</div>') !!}
</div>

		</div>
    </div>
    <div class="m-portlet__foot m-portlet__foot--fit m-portlet__foot-no-border">
        <div class="m-form__actions m-form__actions--right">
            <button class="btn btn-brand m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-save"></i><span>@lang('Save')</span></span></button>
            <a href="{{ route('history_calls.index') }}" class="btn btn-secondary m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-ban"></i><span>@lang('Cancel')</span></span></a>
        </div>
    </div>
</form>