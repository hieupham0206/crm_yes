@php /** @var \App\Models\PaymentDetail $paymentDetail */ @endphp
<form id="payment_details_form" class="m-form m-form--label-align-right m-form--group-seperator-dashed m-form--state" method="post" action="{{ $action }}">
    @csrf
    @isset($method)
        @method('put')
    @endisset
    <div class="m-portlet__body">
        <div class="form-group m-form__group row">
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('total_paid_real') ? 'has-danger' : ''}}">
                <label for="txt_total_paid_real">{{ $paymentDetail->label('total_paid_real') }}</label>
                <input class="form-control numeric" name="total_paid_real" type="text" id="txt_total_paid_real" value="{{ $paymentDetail->total_paid_real ?? old('total_paid_real')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('total_paid_real', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('pay_date_real') ? 'has-danger' : ''}}">
                <label for="txt_pay_date_real">{{ $paymentDetail->label('pay_date_real') }}</label>
                <input class="form-control text-datepicker" name="pay_date_real" type="text" id="txt_pay_date_real" value="{{ $paymentDetail->pay_date_real ?? old('pay_date_real')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('pay_date_real', '<div class="form-control-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="form-group m-form__group row">
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('payment_method') ? 'has-danger' : ''}}">
                <label for="select_payment_method">{{ $paymentCost->label('payment_method') }}</label>
                <select name="payment_method" id="select_payment_method" class="select" required>
                    <option></option>
                    @foreach ($paymentCost->payment_methods as $key => $paymentMethod)
                        <option value="{{ $key }}" {{ $paymentDetail->payment_cost->payment_method === $key ? 'selected' : '' }}>{{ $paymentMethod }}</option>
                    @endforeach
                </select>
                <span class="m-form__help"></span>
                {!! $errors->first('payment_method', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('bank_name') ? 'has-danger' : ''}}">
                <label for="select_bank">{{ $paymentCost->label('bank_name') }}</label>
                <input type="hidden" name="bank_name" id="txt_bank_name" value="{{ $paymentDetail->payment_cost->bank_name }}">
                <select name="select_bank_name" id="select_bank" class="select" disabled>
                    <option></option>
                    <option value="1" {{ $paymentDetail->payment_cost->bank_name ? 'selected' : '' }}>{{ $paymentDetail->payment_cost->bank_name }}</option>
                </select>
                <span class="m-form__help"></span>
                {!! $errors->first('bank_name', '<div class="form-control-feedback">:message</div>') !!}
            </div>

            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('payment_installment_id') ? 'has-danger' : ''}}">
                <label for="select_payment_installment_id">{{ 'Trả góp' }}</label>
                <select name="payment_installment_id" id="select_payment_installment_id" class="select" required>
                    <option></option>
                    @foreach ($paymentCost->payment_method_installments as $key => $paymentMethod)
                        <option value="{{ $key }}" {{ optional($paymentDetail->payment_installment)->payment_method === $key ? 'selected' : '' }}>{{ $paymentMethod }}</option>
                    @endforeach
                </select>
                <span class="m-form__help"></span>
                {!! $errors->first('payment_installment_id', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('bank_name_installment') ? 'has-danger' : ''}}">
                <label for="select_bank_installment">{{ 'Ngân hàng trả góp' }}</label>
                <input type="hidden" name="bank_name_installment" id="txt_bank_name_installment">
                <select id="select_bank_installment" class="select" disabled>
                    <option></option>
                    <option value="1" {{ optional($paymentDetail->payment_installment)->bank_name ? 'selected' : '' }}>{{ optional($paymentDetail->payment_installment)->bank_name }}</option>
                </select>
                <span class="m-form__help"></span>
                {!! $errors->first('bank_name_installment', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('cost') ? 'has-danger' : ''}}">
                <label for="txt_cost">{{ $paymentCost->label('cost') }}</label>
                <input class="form-control" name="payment_fee" type="text" id="txt_cost" value="{{ $paymentDetail->payment_cost->cost ?? old('cost')}}" readonly placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('cost', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('bank_no') ? 'has-danger' : ''}}">
                <label for="txt_bank_no">{{ $paymentCost->label('bank_no') }}</label>
                <input class="form-control" name="bank_no" type="text" id="txt_bank_no" value="{{ $paymentCost->bank_no ?? old('bank_no')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('bank_no', '<div class="form-control-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="form-group m-form__group row">
            <div class="col-12 m-form__group-sub {{ $errors->has('note') ? 'has-danger' : ''}}">
                <label for="textarea_note">{{ $paymentDetail->label('note') }}</label>
                <textarea name="note" id="textarea_note" cols="30" rows="5" class="form-control"></textarea>
                <span class="m-form__help"></span>
                {!! $errors->first('note', '<div class="form-control-feedback">:message</div>') !!}
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