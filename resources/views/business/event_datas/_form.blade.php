@php /** @var \App\Models\EventData $eventData */ @endphp
<form id="event_datas_form" class="m-form m-form--label-align-right m-form--group-seperator-dashed m-form--state" method="post" action="{{ $action }}">
    @csrf
    @isset($method)
        @method('put')
    @endisset
    <div class="m-portlet__body">
        <div class="form-group m-form__group row">
            <input type="hidden" name="id" id="txt_event_data_id">
            <div class="col-sm-12 col-md-4 m-form__group-sub">
                <label for="txt_voucher">{{ $eventData->label('Voucher') }}</label>
                <input class="form-control" name="voucher" type="text" id="txt_voucher" value="{{ $eventData->code }}" placeholder="{{ __('Enter value') }}" disabled>
            </div>
            <div class="col-sm-12 col-md-4 m-form__group-sub">
                <label for="select_to">{{ $eventData->label('TO') }}</label>
                {{--<input class="form-control" name="to" type="text" id="txt_to" value="" placeholder="{{ __('Enter value') }}" disabled>--}}
                <select name="to_id" id="select_to">
                    <option></option>
                    @if ($eventData->to_id)
                        <option value="{{ $eventData->to_id }}" selected>{{ $eventData->to->name }}</option>
                    @endif
                </select>
            </div>
            <div class="col-sm-12 col-md-4 m-form__group-sub">
                <label for="select_rep">{{ $eventData->label('REP') }}</label>
                {{--<input class="form-control" name="rep" type="text" id="txt_rep" value="" placeholder="{{ __('Enter value') }}" disabled>--}}
                <select name="rep_id" id="select_rep">
                    <option></option>
                    @if ($eventData->rep_id)
                        <option value="{{ $eventData->rep_id }}" selected>{{ $eventData->rep->name }}</option>
                    @endif
                </select>
            </div>
            <div class="col-sm-12 col-md-4 m-form__group-sub">
                <label class="m-checkbox">
                    <input type="checkbox" name="hot_bonus" value="1" {{ $eventData->hot_bonus == 1 ? 'checked' : '' }}> Hot Bonus
                    <span></span>
                </label>
            </div>
        </div>
        <div class="form-group m-form__group row">
            <div class="col-sm-12 col-md-4 m-form__group-sub">
                <label for="txt_note">{{ $eventData->label('Note') }}</label>
                <textarea class="form-control" name="note" row="4" id="txt_note" placeholder="{{ __('Enter value') }}">{{ $eventData->note }}</textarea>
            </div>
        </div>
    </div>
    <div class="m-portlet__foot m-portlet__foot--fit m-portlet__foot-no-border">
        <div class="m-form__actions m-form__actions--right">
            <button class="btn btn-brand m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-save"></i><span>@lang('Save')</span></span></button>
            <a href="{{ route('event_datas.index') }}" class="btn btn-secondary m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-ban"></i><span>@lang('Cancel')</span></span></a>
        </div>
    </div>
</form>