<form id="callbacks_search_form">
    <div class="form-group m-form__group row">
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="txt_from_date">{{ __('From Date') }}</label>
                <input class="form-control text-datepicker" name="from_date" id="txt_from_date" value="" autocomplete="off">
            </div>
        </div>
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="txt_to_date">{{ __('To Date') }}</label>
                <input class="form-control text-datepicker" name="to_date" id="txt_to_date" value="" autocomplete="off">
            </div>
        </div>
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="txt_phone">{{ 'Số điện thoại'  }}</label>
                <input class="form-control" name="phone" id="txt_phone" value="" autocomplete="off">
            </div>
        </div>
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="select_user_id">{{ $callback->label('user') }}</label>
                <select name="user_id" id="select_user_id" data-url="{{ route('users.list') }}" class="select2-ajax" data-column="name">
                    <option></option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="select_lead_id">{{ $callback->label('lead') }}</label>
                <select name="lead_id" id="select_lead_id" data-url="{{ route('leads.list') }}" class="select2-ajax" data-column="name">
                    <option></option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-3 m-form__group-sub mt-6">
            <button class="btn btn-brand m-btn m-btn--custom m-btn--icon" id="btn_filter"><span> <i class="fa fa-search"></i> <span>@lang('Search')</span> </span></button>
            <button type="button" class="btn btn-secondary m-btn m-btn--custom m-btn--icon" id="btn_reset_filter"><span> <i class="fa fa-undo-alt"></i> <span>@lang('Reset')</span> </span></button>
        </div>
    </div>
</form>