@php /** @var \App\Models\PaymentDetail $paymentDetail */ @endphp

<form id="payment_details_form" class="m-form m-form--label-align-right m-form--group-seperator-dashed m-form--state" method="post" action="{{ $action }}">
    @csrf
    @isset($method)
        @method('put')
    @endisset
    <div class="m-portlet__body">
        <div class="form-group m-form__group row">
			<div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('total_paid_deal') ? 'has-danger' : ''}}">
    <label for="txt_total_paid_deal">{{ $paymentDetail->label('total_paid_deal') }}</label>
    <input class="form-control" name="total_paid_deal" type="text" id="txt_total_paid_deal" value="{{ $paymentDetail->total_paid_deal ?? old('total_paid_deal')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
<span class="m-form__help"></span>
    {!! $errors->first('total_paid_deal', '<div class="form-control-feedback">:message</div>') !!}
</div>

		</div>
    </div>
    <div class="m-portlet__foot m-portlet__foot--fit m-portlet__foot-no-border">
        <div class="m-form__actions m-form__actions--right">
            <button class="btn btn-brand m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-save"></i><span>@lang('Save')</span></span></button>
            <a href="{{ route('payment_details.index') }}" class="btn btn-secondary m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-ban"></i><span>@lang('Cancel')</span></span></a>
        </div>
    </div>
</form>