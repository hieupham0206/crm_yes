@php /** @var \App\Models\Contract $contract */ @endphp
<form id="contracts_form" class="m-form m-form--label-align-right m-form--group-seperator-dashed m-form--state" method="post" action="{{ $action }}">
    @csrf
    @isset($method)
        @method('put')
    @endisset
    <div class="m-portlet__body">
        <input type="hidden" name="event_data_id" value="{{ optional($eventData)->id }}">
        {{--MEMBER INFO--}}
        <div class="form-group m-form__group row">
            <div class="col-md-6 row">
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('title') ? 'has-danger' : ''}}">
                    <label for="select_title">{{ $member->label('title') }}</label>
                    {{--<input class="form-control" name="title" type="text" id="txt_title" value="{{ $lead->title ?? old('title')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">--}}
                    <select name="title" class="form-control select" id="select_title">
                        <option></option>
                        @foreach ($member->titles as $key => $title)
                            <option value="{{ $title }}" {{ $member->title == $title || (! $member->exists && $key === 1) ? ' selected' : '' }}>{{ $title }}</option>
                        @endforeach
                    </select>
                    <span class="m-form__help"></span>
                    {!! $errors->first('title', '<div class="form-control-feedback">:message</div>') !!}
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('name') ? 'has-danger' : ''}}">
                    <label for="txt_name">{{ $member->label('name') }}</label>
                    <input class="form-control" name="name" type="text" id="txt_name" value="{{ $member->name ?? old('name')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                    <span class="m-form__help"></span>
                    {!! $errors->first('name', '<div class="form-control-feedback">:message</div>') !!}
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('email') ? 'has-danger' : ''}}">
                    <label for="txt_email">{{ $member->label('email') }}</label>
                    <input class="form-control" name="email" type="email" id="txt_email" value="{{ $member->email ?? old('email')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                    <span class="m-form__help"></span>
                    {!! $errors->first('email', '<div class="form-control-feedback">:message</div>') !!}
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('phone') ? 'has-danger' : ''}}">
                    <label for="txt_phone">{{ $member->label('phone') }}</label>
                    <input class="form-control" name="phone" type="text" id="txt_phone" value="{{ $member->phone ?? old('phone')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                    <span class="m-form__help"></span>
                    {!! $errors->first('phone', '<div class="form-control-feedback">:message</div>') !!}
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('birthday') ? 'has-danger' : ''}}">
                    <label for="txt_birthday">{{ $member->label('birthday') }}</label>
                    <input class="form-control text-datepicker" name="birthday" type="text" id="txt_birthday" value="{{ optional($member->birthday)->format('d-m-Y') ?? old('birthday')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                    <span class="m-form__help"></span>
                    {!! $errors->first('birthday', '<div class="form-control-feedback">:message</div>') !!}
                </div>

                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('identity') ? 'has-danger' : ''}}">
                    <label for="txt_husband_identity">{{ $member->label('identity') }}</label>
                    <input class="form-control identity-number" name="identity" type="text" id="txt_identity" value="{{ $member->identity ?? old('identity')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                    <span class="m-form__help"></span>
                    {!! $errors->first('identity', '<div class="form-control-feedback">:message</div>') !!}
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('identity_address') ? 'has-danger' : ''}}">
                    <label for="txt_identity_address">{{ $member->label('identity_address') }}</label>
                    {{--<input class="form-control" name="identity_address" type="text" id="txt_identity_address" value="{{ $member->identity_address ?? old('identity_address')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">--}}
                    <select name="identity_address" class="form-control select2-ajax" id="select_identity_address" data-url="{{ route('leads.provinces.table') }}">
                        <option></option>
                        @if($member->identity_address)
                            <option value="{{ $member->identity_address }}" selected>{{ $member->identity_address->name }}</option>
                        @endif
                    </select>
                    <span class="m-form__help"></span>
                    {!! $errors->first('identity_address', '<div class="form-control-feedback">:message</div>') !!}
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('identity_date') ? 'has-danger' : ''}}">
                    <label for="txt_identity_date">{{ $member->label('identity_date') }}</label>
                    <input class="form-control text-datepicker" name="identity_date" type="text" id="txt_identity_date" value="{{ optional($member->identity_date)->format('d-m-Y') ?? old('husband_identity_date')}}" placeholder="{{ __('Enter value') }}"
                           autocomplete="off">
                    <span class="m-form__help"></span>
                    {!! $errors->first('husband_identity_date', '<div class="form-control-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-md-6 row">
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_title') ? 'has-danger' : ''}}">
                    <label for="select_spouse_title">{{ $member->label('spouse_title') }}</label>
                    {{--<input class="form-control" name="spouse_title" type="text" id="txt_spouse_title" value="{{ $member->spouse_title ?? old('spouse_title')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">--}}
                    <select name="spouse_title" class="form-control select" id="select_spouse_title">
                        <option></option>
                        @foreach ($member->titles as $key => $title)
                            <option value="{{ $title }}" {{ $member->spouse_title == $title || (! $member->exists && $key === 1) ? ' selected' : '' }}>{{ $title }}</option>
                        @endforeach
                    </select>
                    <span class="m-form__help"></span>
                    {!! $errors->first('spouse_title', '<div class="form-control-feedback">:message</div>') !!}
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_name') ? 'has-danger' : ''}}">
                    <label for="txt_spouse_name">{{ $member->label('name') }}</label>
                    <input class="form-control" name="spouse_name" type="text" id="txt_spouse_name" value="{{ $member->spouse_name ?? old('spouse_name')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                    <span class="m-form__help"></span>
                    {!! $errors->first('spouse_name', '<div class="form-control-feedback">:message</div>') !!}
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_email') ? 'has-danger' : ''}}">
                    <label for="txt_spouse_email">{{ $member->label('email') }}</label>
                    <input class="form-control" name="spouse_email" type="email" id="txt_spouse_email" value="{{ $member->spouse_email ?? old('spouse_email')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                    <span class="m-form__help"></span>
                    {!! $errors->first('spouse_email', '<div class="form-control-feedback">:message</div>') !!}
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_phone') ? 'has-danger' : ''}}">
                    <label for="txt_spouse_phone">{{ $member->label('phone') }}</label>
                    <input class="form-control" name="spouse_phone" type="text" id="txt_spouse_phone" value="{{ $member->spouse_phone ?? old('spouse_phone')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                    <span class="m-form__help"></span>
                    {!! $errors->first('spouse_phone', '<div class="form-control-feedback">:message</div>') !!}
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_birthday') ? 'has-danger' : ''}}">
                    <label for="txt_spouse_birthday">{{ $member->label('spouse_birthday') }}</label>
                    <input class="form-control text-datepicker" name="spouse_birthday" type="text" id="txt_spouse_birthday" value="{{ optional($member->spouse_birthday)->format('d-m-Y') ?? old('spouse_birthday')}}" placeholder="{{ __('Enter value') }}"
                           autocomplete="off">
                    <span class="m-form__help"></span>
                    {!! $errors->first('spouse_birthday', '<div class="form-control-feedback">:message</div>') !!}
                </div>

                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_identity') ? 'has-danger' : ''}}">
                    <label for="txt_spouse_identity">{{ $member->label('spouse_identity') }}</label>
                    <input class="form-control identity-number" name="spouse_identity" type="text" id="txt_spouse_identity" value="{{ $member->spouse_identity ?? old('spouse_identity')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                    <span class="m-form__help"></span>
                    {!! $errors->first('spouse_identity', '<div class="form-control-feedback">:message</div>') !!}
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_identity_address') ? 'has-danger' : ''}}">
                    <label for="txt_spouse_identity_address">{{ $member->label('spouse_identity_address') }}</label>
                    {{--<input class="form-control" name="spouse_identity_address" type="text" id="txt_spouse_identity_address" value="{{ $member->spouse_identity_address ?? old('spouse_identity_address')}}" placeholder="{{ __('Enter value') }}"--}}
                    {{--autocomplete="off">--}}
                    <select name="spouse_identity_address" class="form-control select2-ajax" id="select_spouse_identity_address" data-url="{{ route('leads.provinces.table') }}">
                        <option></option>
                        @if($member->spouse_identity_address)
                            <option value="{{ $member->spouse_identity_address }}" selected>{{ $member->spouse_identity_address->name }}</option>
                        @endif
                    </select>
                    <span class="m-form__help"></span>
                    {!! $errors->first('spouse_identity_address', '<div class="form-control-feedback">:message</div>') !!}
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_identity_date') ? 'has-danger' : ''}}">
                    <label for="txt_spouse_identity_date">{{ $member->label('spouse_identity_date') }}</label>
                    <input class="form-control text-datepicker" name="spouse_identity_date" type="text" id="txt_spouse_identity_date" value="{{ optional($member->spouse_identity_date)->format('d-m-Y') ?? old('spouse_identity_date')}}" placeholder="{{ __
                ('Enter value') }}" autocomplete="off">
                    <span class="m-form__help"></span>
                    {!! $errors->first('spouse_identity_date', '<div class="form-control-feedback">:message</div>') !!}
                </div>
            </div>

            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('address') ? 'has-danger' : ''}}">
                <label for="txt_address">{{ $member->label('address') }}</label>
                <input class="form-control" name="address" type="text" id="txt_address" value="{{ $member->address ?? old('address')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('address', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('state') ? 'has-danger' : ''}}">
                <label for="select_province">{{ $member->label('province') }}</label>
                <select name="province_id" class="form-control" id="select_province" data-url="{{ route('leads.provinces.table') }}">
                    <option></option>
                    @if ($member->province_id)
                        <option value="{{ $member->province_id }}" selected>{{ $member->province->name }}</option>
                    @endif
                </select>
                <span class="m-form__help"></span>
                {!! $errors->first('province', '<div class="form-control-feedback">:message</div>') !!}
            </div>
        </div>
        {{--<div class="form-group m-form__group row"></div>--}}
        {{--END MEMBER INFO--}}

        {{--CONTRACT--}}
        <div class="form-group m-form__group row">
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('contract_no') ? 'has-danger' : ''}}">
                <label for="txt_contract_no">{{ $contract->label('contract_no') }}</label>
                <input class="form-control numeral" name="contract_no" type="text" id="txt_contract_no" value="{{ $contract->contract_no ?? old('contract_no')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('contract_no', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('signed_date') ? 'has-danger' : ''}}">
                <label for="txt_signed_date">{{ $contract->label('signed_date') }}</label>
                <input class="form-control text-datepicker" name="signed_date" type="text" id="txt_signed_date" value="{{ $contract->signed_date ?? old('signed_date')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('signed_date', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('amount') ? 'has-danger' : ''}}">
                <label for="txt_amount">{{ $contract->label('amount') }}</label>
                <input class="form-control numeric" name="amount" type="text" id="txt_amount" value="{{ $contract->amount ?? old('amount')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('amount', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('membership') ? 'has-danger' : ''}}">
                <label for="select_membership">{{ $contract->label('membership') }}</label>
                <select name="membership" id="select_membership" class="select">
                    <option></option>
                    @foreach ($contract->memberships as $key => $membership)
                        <option value="{{ $key }}" {{ $contract->membership == $key ? 'checked' : '' }}>{{ $membership }}</option>
                    @endforeach
                </select>
                <span class="m-form__help"></span>
                {!! $errors->first('membership', '<div class="form-control-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="form-group m-form__group row">
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('room_type') ? 'has-danger' : ''}}">
                <label for="select_room_type">{{ $contract->label('room_type') }}</label>
                <select name="room_type" id="select_room_type" class="select">
                    <option></option>
                    @foreach ($contract->room_types as $key => $roomType)
                        <option value="{{ $key }}" {{ $contract->room_type == $key ? 'checked' : '' }}>{{ $roomType }}</option>
                    @endforeach
                </select>
                <span class="m-form__help"></span>
                {!! $errors->first('room_type', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('limit') ? 'has-danger' : ''}}">
                <label for="select_limit">{{ $contract->label('limit') }}</label>
                <select name="limit" id="select_limit" class="select">
                    <option></option>
                    @foreach ($contract->limits as $key => $limit)
                        <option value="{{ $key }}" {{ $contract->limit == $key ? 'checked' : '' }}>{{ $limit }}</option>
                    @endforeach
                </select>
                <span class="m-form__help"></span>
                {!! $errors->first('limit', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('start_date') ? 'has-danger' : ''}}">
                <label for="txt_start_date">{{ $contract->label('start_date') }}</label>
                <input class="form-control text-datepicker" name="start_date" type="text" id="txt_start_date" value="{{ $contract->start_date ?? old('start_date')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('start_date', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('end_time') ? 'has-danger' : ''}}">
                <label for="txt_end_time">{{ $contract->label('end_time') }}</label>
                <input class="form-control" name="end_time" type="text" id="txt_end_time" value="{{ $contract->end_time ?? old('end_time')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('end_time', '<div class="form-control-feedback">:message</div>') !!}
            </div>
        </div>
        {{--END CONTRACT--}}

        {{--PAYMENT_DETAIL--}}
        <div class="form-group m-form__group row">
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('total_paid_deal') ? 'has-danger' : ''}}">
                <label for="txt_total_paid_deal">{{ $contract->label('total_paid_deal') }}</label>
                <input class="form-control numeric" name="total_paid_deal" type="text" id="txt_total_paid_deal" value="{{ $contract->total_paid_deal ?? old('total_paid_deal')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('total_paid_deal', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('pay_date') ? 'has-danger' : ''}}">
                <label for="txt_pay_date">{{ $contract->label('pay_date') }}</label>
                <input class="form-control text-datepicker" name="pay_date" type="text" id="txt_pay_date" value="{{ $contract->pay_date ?? old('pay_date')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('pay_date', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('payment_method') ? 'has-danger' : ''}}">
                <label for="select_payment_method">{{ $paymentCost->label('payment_method') }}</label>
                <select name="payment_method" id="select_payment_method" class="select">
                    <option></option>
                    @foreach ($paymentCost->payment_methods as $key => $paymentMethod)
                        <option value="{{ $key }}">{{ $paymentMethod }}</option>
                    @endforeach
                </select>
                <span class="m-form__help"></span>
                {!! $errors->first('payment_method', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('bank_name') ? 'has-danger' : ''}}">
                <label for="select_bank">{{ $paymentCost->label('bank_name') }}</label>
                <select name="bank_name" id="select_bank" class="select" disabled>
                    <option></option>
                </select>
                <span class="m-form__help"></span>
                {!! $errors->first('bank_name', '<div class="form-control-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="form-group m-form__group row">
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('cost') ? 'has-danger' : ''}}">
                <label for="txt_cost">{{ $paymentCost->label('cost') }}</label>
                <input class="form-control" name="cost" type="text" id="txt_cost" value="{{ $paymentCost->cost ?? old('cost')}}" readonly placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('cost', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('bank_no') ? 'has-danger' : ''}}">
                <label for="txt_bank_no">{{ $paymentCost->label('bank_no') }}</label>
                <input class="form-control" name="bank_no" type="text" id="txt_bank_no" value="{{ $paymentCost->bank_no ?? old('bank_no')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('bank_no', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('year_cost') ? 'has-danger' : ''}}">
                <label for="txt_year_cost">{{ $contract->label('year_cost') }}</label>
                <input class="form-control numeric" name="year_cost" type="text" id="txt_year_cost" value="{{ $contract->year_cost ?? old('year_cost')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('year_cost', '<div class="form-control-feedback">:message</div>') !!}
            </div>
        </div>

        @if (! $contract->exists)
            <div class="form-group m-form__group row">
                <div class="col-lg-3">
                    <label for="txt_payment_time">Số lần thanh toán</label>
                    <input class="form-control" name="payment_time" type="text" id="txt_payment_time" value="" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                </div>
                <div class="col-lg-1">
                    <button type="button" class="btn btn-accent m-btn m-btn--custom mt-6" id="btn_add_payment_detail">{{ __('Add') }}</button>
                </div>
            </div>
        @endif

        <div class="form-group m-form__group row">
            <div class="col-lg-4">
                <table class="table table-hover table-bordered nowrap" id="table_payment_detail">
                    <thead class="">
                    <tr>
                        <th>Ngày thanh toán</th>
                        <th>Số tiền thanh toán</th>
                        {{--<th>@lang('Actions')</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @isset ($paymentDetails)
                        @foreach ($paymentDetails as $paymentDetail)
                            <tr>
                                <td><input class="form-control txt-payment-date" value="{{ $paymentDetail->pay_date }}" name="PaymentDetail[payment_date][{{ $loop->index }}][]" type="text" autocomplete="off"></td>
                                <td><input class="form-control txt-total-paid-deal" value="{{ $paymentDetail->total_paid_detail }}" name="PaymentDetail[total_paid_deal][{{ $loop->index }}][]" type="text" autocomplete="off"></td>
                            </tr>
                        @endforeach
                    @endisset
                    </tbody>
                </table>
            </div>
        </div>
        <div class="form-group m-form__group row">
            <div class="col-12 m-form__group-sub {{ $errors->has('note') ? 'has-danger' : ''}}">
                <label for="textarea_note">{{ $paymentCost->label('note') }}</label>
                <textarea name="note" id="textarea_note" cols="30" rows="5" class="form-control"></textarea>
                <span class="m-form__help"></span>
                {!! $errors->first('note', '<div class="form-control-feedback">:message</div>') !!}
            </div>
        </div>
        {{--END PAYMENT_DETAIL--}}
    </div>
    <div class="m-portlet__foot m-portlet__foot--fit m-portlet__foot-no-border">
        <div class="m-form__actions m-form__actions--right">
            <button class="btn btn-brand m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-save"></i><span>@lang('Save')</span></span></button>
            <a href="{{ route('contracts.index') }}" class="btn btn-secondary m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-ban"></i><span>@lang('Cancel')</span></span></a>
        </div>
    </div>
</form>