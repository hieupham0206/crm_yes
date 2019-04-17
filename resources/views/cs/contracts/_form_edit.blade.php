@php /** @var \App\Models\Commission $contract */ @endphp
<form id="contracts_form" class="m-form m-form--label-align-right m-form--group-seperator-dashed m-form--state" method="post" action="{{ $action }}">
    @csrf
    @isset($method)
        @method('put')
    @endisset
    <div class="m-portlet__body">
        {{--<input type="hidden" name="event_data_id" value="{{ optional($eventData)->id }}">--}}

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
                            <option value="{{ $member->identity_address }}" selected>{{ optional($member->identityProvince)->name }}</option>
                        @endif
                    </select>
                    <span class="m-form__help"></span>
                    {!! $errors->first('identity_address', '<div class="form-control-feedback">:message</div>') !!}
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('identity_date') ? 'has-danger' : ''}}">
                    <label for="txt_identity_date">{{ $member->label('identity_date') }}</label>
                    <input class="form-control text-datepicker" name="identity_date" type="text" id="txt_identity_date" value="{{ $member->identity_date && $member->identity_date->timestamp > 0 ?
                    optional($member->identity_date)->format('d-m-Y') : old('husband_identity_date')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
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
                            <option value="{{ $member->spouse_identity_address }}" selected>{{ optional($member->spouseIdentityProvince)->name }}</option>
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

            <div class="col-12 row">
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub">
                    <label for="select_province">Tỉnh/Thành phố</label>
                    <select name="city" class="form-control select" id="select_address_city">
                        <option></option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->city_code }}">{{ $city->city_name }}</option>
                        @endforeach
                    </select>
                    <span class="m-form__help"></span>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub">
                    <label for="select_province">Quận/huyện</label>
                    <select name="city" class="form-control select" id="select_address_county" data-url={{ route('contracts.county.list') }}>
                        <option></option>
                    </select>
                    <span class="m-form__help"></span>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub">
                    <label for="select_province">Phường/xã</label>
                    <select name="city" class="form-control" id="select_address_ward" data-url={{ route('contracts.ward.list') }}>
                        <option></option>
                    </select>
                    <span class="m-form__help"></span>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('address') ? 'has-danger' : ''}}">
                    <label for="txt_address">Địa chỉ liên lạc</label>
                    <input class="form-control" type="text" id="txt_address" value="{{ $member->address ?? old('address')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                    <input name="address" type="hidden"  id="txt_hidden_address" value="{{ $member->address ?? old('address')}}">
                    <span class="m-form__help"></span>
                    {!! $errors->first('address', '<div class="form-control-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 row">
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub">
                    <label for="select_province">Tỉnh/Thành phố</label>
                    <select name="city" class="form-control select" id="select_tmp_address_city">
                        <option></option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->city_code }}">{{ $city->city_name }}</option>
                        @endforeach
                    </select>
                    <span class="m-form__help"></span>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub">
                    <label for="select_province">Quận/huyện</label>
                    <select name="city" class="form-control select" id="select_tmp_address_county" data-url={{ route('contracts.county.list') }}>
                        <option></option>
                    </select>
                    <span class="m-form__help"></span>
                </div>
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub">
                    <label for="select_province">Phường/xã</label>
                    <select name="city" class="form-control" id="select_tmp_address_ward" data-url={{ route('contracts.ward.list') }}>
                        <option></option>
                    </select>
                    <span class="m-form__help"></span>
                </div>

                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('temp_address') ? 'has-danger' : ''}}">
                    <label for="txt_temp_address">Địa chỉ thường trú</label>
                    <input class="form-control" name="temp_address" type="text" id="txt_temp_address" value="{{ $member->temp_address ?? old('temp_address')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                    <input name="temp_address" type="hidden"  id="txt_hidden_temp_address" value="{{ $member->temp_address ?? old('temp_address')}}">
                    <span class="m-form__help"></span>
                    {!! $errors->first('temp_address', '<div class="form-control-feedback">:message</div>') !!}
                </div>
            </div>
            <div class="col-12 row">
                <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('city') ? 'has-danger' : ''}}">
                    <label for="select_province">Chi nhánh</label>
                    <select name="city" class="form-control" id="select_province" data-url="{{ route('leads.provinces.table') }}" required>
                        <option></option>
                        @if ($member->city)
                            <option value="{{ $member->city }}" selected>{{ $member->province->name }}</option>
                        @endif
                    </select>
                    <span class="m-form__help"></span>
                    {!! $errors->first('city', '<div class="form-control-feedback">:message</div>') !!}
                </div>

            </div>
        </div>
        {{--<div class="form-group m-form__group row"></div>--}}
        {{--END MEMBER INFO--}}

        {{--CONTRACT--}}
        <div class="form-group m-form__group row">
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('contract_no') ? 'has-danger' : ''}}">
                <label for="txt_contract_no">{{ $contract->label('contract_no') }}</label>
                <input readonly class="form-control numeral" name="contract_no" type="text" id="txt_contract_no" value="{{ $contract->contract_no ?? old('contract_no')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('contract_no', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('signed_date') ? 'has-danger' : ''}}">
                <label for="txt_signed_date">{{ $contract->label('signed_date') }}</label>
                <input class="form-control text-datepicker" name="signed_date" type="text" id="txt_signed_date" value="{{ optional($contract->signed_date)->format('d-m-Y') ?? old('signed_date')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('signed_date', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('amount') ? 'has-danger' : ''}}">
                <label for="txt_amount">{{ $contract->label('amount') }}</label>
                <input class="form-control numeric" name="amount" type="text" id="txt_amount" value="{{ number_format($contract->amount) ?? old('amount')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('amount', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('membership') ? 'has-danger' : ''}}">
                <label for="select_membership">{{ $contract->label('membership') }}</label>
                <select name="membership" id="select_membership" class="select">
                    <option></option>
                    @foreach ($contract->memberships as $key => $membership)
                        <option value="{{ $key }}" {{ $contract->membership == $key ? 'selected' : '' }}>{{ $membership }}</option>
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
                        <option value="{{ $key }}" {{ $contract->room_type == $key ? 'selected' : '' }}>{{ $roomType }}</option>
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
                        <option value="{{ $key }}" {{ $contract->limit == $key ? 'selected' : '' }}>{{ $limit }}</option>
                    @endforeach
                </select>
                <span class="m-form__help"></span>
                {!! $errors->first('limit', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('start_date') ? 'has-danger' : ''}}">
                <label for="txt_start_date">{{ $contract->label('start_date') }}</label>
                <input class="form-control text-datepicker" name="start_date" type="text" id="txt_start_date" value="{{ optional($contract->start_date)->format('d-m-Y') ?? old('start_date')}}" required placeholder="{{ __('Enter value') }}" autocomplete="off">
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
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('payment_method') ? 'has-danger' : ''}}">
                <label for="select_payment_method">{{ $paymentCost->label('payment_method') }}</label>
                <input class="form-control" name="cost" type="text" id="txt_payment_cost" value="{{ $firstPaymentDetail->payment_cost->payment_method_text}}" readonly placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('payment_method', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('bank_name') ? 'has-danger' : ''}}">
                <label for="select_bank">{{ $paymentCost->label('bank_name') }}</label>
                <input class="form-control" name="cost" type="text" id="txt_bank_name" value="{{ $firstPaymentDetail->payment_cost->bank_name ?? old('bank_name')}}" readonly placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('bank_name', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('cost') ? 'has-danger' : ''}}">
                <label for="txt_cost">{{ $paymentCost->label('cost') }}</label>
                <input class="form-control" name="cost" type="text" id="txt_cost" value="{{ $firstPaymentDetail->payment_cost->cost ?? old('cost')}}" readonly placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('cost', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('bank_no') ? 'has-danger' : ''}}">
                <label for="txt_bank_no">{{ $paymentCost->label('bank_no') }}</label>
                <input class="form-control" name="bank_no" type="text" id="txt_bank_no" value="{{ $firstPaymentDetail->bank_no ?? old('bank_no')}}" readonly placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('bank_no', '<div class="form-control-feedback">:message</div>') !!}
            </div>
        </div>
        <div class="form-group m-form__group row">
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('year_cost') ? 'has-danger' : ''}}">
                <label for="txt_year_cost">{{ $contract->label('year_cost') }}</label>
                <input class="form-control numeric" name="year_cost" type="text" id="txt_year_cost" value="{{ number_format($contract->year_cost) ?? old('year_cost')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                <span class="m-form__help"></span>
                {!! $errors->first('year_cost', '<div class="form-control-feedback">:message</div>') !!}
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('num_of_payment') ? 'has-danger' : ''}}">
                <label for="txt_payment_time">Số lần thanh toán</label>
                <input class="form-control" name="num_of_payment" type="text" id="txt_payment_time" value="{{ $contract->num_of_payment }}" required placeholder="{{ __('Enter value') }}" autocomplete="off" readonly>
                <span class="m-form__help"></span>
                {!! $errors->first('num_of_payment', '<div class="form-control-feedback">:message</div>') !!}
            </div>
        </div>
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
                                <td>{{ optional($paymentDetail->pay_date)->format('d-m-Y') }}</td>
                                <td>{{ number_format($paymentDetail->total_paid_deal) }}</td>
                                {{--<td>--}}
                                    {{--<input type="hidden" name="PaymentDetail[id][{{ $loop->index }}][]" value="{{ $paymentDetail->id }}">--}}
                                    {{--<input class="form-control txt-payment-date" value="{{ $paymentDetail->pay_date->format('d-m-Y') }}" name="PaymentDetail[pay_date][{{ $loop->index }}][]" type="text" autocomplete="off">--}}
                                {{--</td>--}}
                                {{--<td><input class="form-control txt-total-paid-deal" value="{{ number_format($paymentDetail->total_paid_deal) }}" name="PaymentDetail[total_paid_deal][{{ $loop->index }}][]" type="text" autocomplete="off"></td>--}}
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
                <textarea name="note" id="textarea_note" cols="30" rows="5" class="form-control" readonly>{{ $firstPaymentDetail->note }}</textarea>
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