<form id="event_datas_search_form">
    <div class="form-group m-form__group row">
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="select_lead">{{ $eventData->label('lead') }}</label>
                <select class="select" name="lead_id" id="select_lead">
                    <option></option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="select_to">{{ $eventData->label('to') }}</label>
                <select class="select" name="to_id" id="select_to">
                    <option></option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="select_rep">{{ $eventData->label('rep') }}</label>
                <select class="select" name="rep_id" id="select_rep">
                    <option></option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="select_state">{{ $eventData->label('state') }}</label>
                <select name="state" id="select_state" class="select">
                    <option></option>
                    @foreach ($eventData->states as $key => $state)
                        <option value="{{ $key }}">{{ $state }}</option>
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