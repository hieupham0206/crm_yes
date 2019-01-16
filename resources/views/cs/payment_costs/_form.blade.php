@php /** @var \App\Models\PaymentCost $paymentCost */ @endphp
<form id="payment_costs_form" class="m-form m-form--label-align-right m-form--group-seperator-dashed m-form--state" method="post" action="{{ $action }}">
    @csrf
    @isset($method)
        @method('put')
    @endisset
    <div class="m-portlet__body">
        <div class="form-group m-form__group row">
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('bank_name') ? 'has-danger' : ''}}">
                <label for="txt_bank_name">{{ $paymentCost->label('bank_name') }}</label>
                <input class="form-control" name="bank_name" type="text" id="txt_bank_name" value="{{ $paymentCost->bank_name ?? old('bank_name')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('bank_name', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('cost') ? 'has-danger' : ''}}">
                <label for="txt_cost">{{ $paymentCost->label('cost') }}</label>
                <input class="form-control" name="cost" type="text" id="txt_cost" value="{{ $paymentCost->cost ?? old('cost')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('cost', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('payment_method') ? 'has-danger' : ''}}">
                <label for="select_payment_method">{{ $paymentCost->label('payment_method') }}</label>
                <select name="payment_method" id="select_payment_method" class="select">
                    <option></option>
                    @foreach ($paymentCost->payment_methods as $key => $payment_method)
                        <option value="{{ $key }}" {{ $paymentCost->payment_method == $key ? 'selected' : '' }}>{{ $payment_method }}</option>
                    @endforeach
                </select>
                <span class="m-form__help"></span>
                {!! $errors->first('payment_method', '<div class="form-control-feedback">:message</div>') !!}
            </div>
        </div>
    </div>
    <div class="m-portlet__foot m-portlet__foot--fit m-portlet__foot-no-border">
        <div class="m-form__actions m-form__actions--right">
            <button class="btn btn-brand m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-save"></i><span>@lang('Save')</span></span></button>
            <a href="{{ route('payment_costs.index') }}" class="btn btn-secondary m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-ban"></i><span>@lang('Cancel')</span></span></a>
        </div>
    </div>
</form>