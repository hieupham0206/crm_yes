<form id="contracts_search_form">
    <div class="form-group m-form__group row">
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="txt_contract_no">{{ $contract->label('contract_no') }}</label>
                <input class="form-control" name="contract_no" id="txt_contract_no">
            </div>
        </div>
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="txt_phone">{{ $contract->label('phone') }}</label>
                <input class="form-control" name="phone" id="txt_phone">
            </div>
        </div>
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="select_member">{{ $eventData->label('lead') }}</label>
                <select name="member_id" id="select_member" class="select2-ajax" data-url="{{ route('contracts.member.list') }}">
                    <option></option>
                </select>
            </div>
        </div>
        <div class="col-12 col-md-3 m-form__group-sub">
            <div class="form-group">
                <label for="select_membership">{{ $contract->label('membership') }}</label>
                <select name="membership" id="select_membership" class="select">
                    <option></option>
                    @foreach ($contract->memberships as $key => $membership)
                        <option value="{{ $key }}">{{ $membership }}</option>
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