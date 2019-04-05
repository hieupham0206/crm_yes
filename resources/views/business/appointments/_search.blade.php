<form id="appointments_search_form">
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
                <label for="txt_created_at">{{ __('Call Date') }}</label>
                <input class="form-control text-datepicker" name="created_at" id="txt_created_at" value="" autocomplete="off">
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
                <label for="txt_code">{{ 'Code' }}</label>
                <input class="form-control" name="code" id="txt_code" value="" autocomplete="off">
            </div>
        </div>
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="select_user_id">{{ $appointment->label('user') }}</label>
                <select name="user_id" id="select_user_id" data-url="{{ route('users.list') }}" class="select2-ajax" data-column="name">
                    <option></option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="select_department_id">{{ $appointment->label('department') }}</label>
                <select name="department_id" id="select_department_id" data-url="{{ route('departments.list') }}" class="form-control select2-ajax">
                    <option value=""></option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="select_state">{{ 'Trạng thái' }}</label>
                <select name="state" id="select_state" class="select">
                    <option></option>
                    <option value="-1">Không sử dụng</option>
                    <option value="1">Sử dụng</option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-3 m-form__group-sub mt-6">
            <button class="btn btn-brand m-btn m-btn--custom m-btn--icon" id="btn_filter"><span> <i class="fa fa-search"></i> <span>@lang('Search')</span> </span></button>
            <button type="button" class="btn btn-secondary m-btn m-btn--custom m-btn--icon" id="btn_reset_filter"><span> <i class="fa fa-undo-alt"></i> <span>@lang('Reset')</span> </span></button>
        </div>
    </div>
</form>