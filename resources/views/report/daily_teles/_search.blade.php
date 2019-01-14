<form id="daily_tele_report_search_form">
    <div class="form-group m-form__group row">
        <div class="col-12 col-md-3 m-form__group-sub">
            <label for="txt_name">{{ $user->label('name') }}</label>
            <input class="form-control" name="name" id="txt_name">
        </div>
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
        <div class="col-12 col-md-3 m-form__group-sub mt-6">
            <button class="btn btn-brand m-btn m-btn--custom m-btn--icon" id="btn_filter"><span> <i class="fa fa-search"></i> <span>@lang('Search')</span> </span></button>
            <button type="button" class="btn btn-secondary m-btn m-btn--custom m-btn--icon" id="btn_reset_filter"><span> <i class="fa fa-undo-alt"></i> <span>@lang('Reset')</span> </span></button>
        </div>
    </div>
</form>