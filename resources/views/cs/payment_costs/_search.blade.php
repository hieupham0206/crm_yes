<form id="payment_costs_search_form">
    <div class="form-group m-form__group row">
        {{--<div class="col-12 col-md-3 m-form__group-sub">--}}
            {{--<div class="form-group">--}}
                {{--<label for="txt_name">{{ $paymentCost->label('name') }}</label>--}}
                {{--<input class="form-control" name="name" id="txt_name">--}}
            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="col-12 col-md-3 m-form__group-sub">--}}
            {{--<div class="form-group">--}}
                {{--<label for="txt_payment_cost">{{ $paymentCost->label('payment cost') }}</label>--}}
                {{--<input class="form-control" name="payment_cost" id="txt_payment_cost">--}}
            {{--</div>--}}
        {{--</div>--}}
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="select_payment_method">{{ $paymentCost->label('payment_method') }}</label>
                <select name="payment_method" class="select" id="select_payment_method">
                    <option></option>
                    @foreach ($paymentCost->payment_methods as $key => $method)
                        <option value="{{ $key }}">{{ $method }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12 col-md-3 m-form__group-sub mt-6">
            <button class="btn btn-brand m-btn m-btn--custom m-btn--icon" id="btn_filter"><span> <i class="fa fa-search"></i> <span>@lang('Search')</span> </span></button>
            <button type="button" class="btn btn-secondary m-btn m-btn--custom m-btn--icon" id="btn_reset_filter"><span> <i class="fa fa-undo-alt"></i> <span>@lang('Reset')</span> </span></button>
        </div>
    </div>
</form>