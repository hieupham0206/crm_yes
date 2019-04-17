@php /** @var \App\Models\Commission $contract */
$breadcrumbs = ['breadcrumb' => 'contracts.show', 'model' => $contract];
@endphp@extends("$layout.app")

@push('scripts')

@endpush

@section('title', __('action.View Model', ['model' => $contract->classLabel(true)]))

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            <form class="m-form m-form--label-align-right m-form--group-seperator-dashed m-form--state">
                <div class="m-portlet__body">
                    {{--MEMBER INFO--}}
                    <div class="form-group m-form__group row">
                        <div class="col-md-6 row">
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('title') ? 'has-danger' : ''}}">
                                <label for="txt_title">{{ $member->label('title') }}</label>
                                <input disabled class="form-control" type="text" id="txt_title" value="{{ $member->title}}">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('name') ? 'has-danger' : ''}}">
                                <label for="txt_name">{{ $member->label('name') }}</label>
                                <input disabled class="form-control" name="name" type="text" id="txt_name" value="{{ $member->name}}">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('email') ? 'has-danger' : ''}}">
                                <label for="txt_email">{{ $member->label('email') }}</label>
                                <input disabled class="form-control" name="email" type="email" id="txt_email" value="{{ $member->email ?? old('email')}}">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('phone') ? 'has-danger' : ''}}">
                                <label for="txt_phone">{{ $member->label('phone') }}</label>
                                <input disabled class="form-control" name="phone" type="text" id="txt_phone" value="{{ $member->phone ?? old('phone')}}">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('birthday') ? 'has-danger' : ''}}">
                                <label for="txt_birthday">{{ $member->label('birthday') }}</label>
                                <input disabled class="form-control" name="birthday" type="text" id="txt_birthday" value="{{ optional($member->birthday)->format('d-m-Y') ?? old('birthday')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('identity') ? 'has-danger' : ''}}">
                                <label for="txt_husband_identity">{{ $member->label('identity') }}</label>
                                <input disabled class="form-control identity-number" name="identity" type="text" id="txt_identity" value="{{ $member->identity ?? old('identity')}}">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('identity_address') ? 'has-danger' : ''}}">
                                <label for="txt_identity_address">{{ $member->label('identity_address') }}</label>
                                <input disabled class="form-control" name="name" type="text" id="txt_identity_address" value="{{ optional($member->identityProvince)->name }}">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('identity_date') ? 'has-danger' : ''}}">
                                <label for="txt_identity_date">{{ $member->label('identity_date') }}</label>
                                <input disabled class="form-control" name="identity_date" type="text" id="txt_identity_date" value="{{ $member->identity_date && $member->identity_date->timestamp > 0 ?
                    optional($member->identity_date)->format('d-m-Y') : ''}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                                <span class="m-form__help"></span>
                            </div>
                        </div>
                        <div class="col-md-6 row">
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_title') ? 'has-danger' : ''}}">
                                <label for="txt_spouse_title">{{ $member->label('spouse_title') }}</label>
                                <input disabled class="form-control" type="text" id="txt_spouse_title" value="{{ $member->spouse_title}}">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_name') ? 'has-danger' : ''}}">
                                <label for="txt_spouse_name">{{ $member->label('name') }}</label>
                                <input disabled class="form-control" name="spouse_name" type="text" id="txt_spouse_name" value="{{ $member->spouse_name ?? old('spouse_name')}}">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_email') ? 'has-danger' : ''}}">
                                <label for="txt_spouse_email">{{ $member->label('email') }}</label>
                                <input disabled class="form-control" name="spouse_email" type="email" id="txt_spouse_email" value="{{ $member->spouse_email ?? old('spouse_email')}}">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_phone') ? 'has-danger' : ''}}">
                                <label for="txt_spouse_phone">{{ $member->label('phone') }}</label>
                                <input disabled class="form-control" name="spouse_phone" type="text" id="txt_spouse_phone" value="{{ $member->spouse_phone ?? old('spouse_phone')}}">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_birthday') ? 'has-danger' : ''}}">
                                <label for="txt_spouse_birthday">{{ $member->label('spouse_birthday') }}</label>
                                <input disabled class="form-control" name="spouse_birthday" type="text" id="txt_spouse_birthday" value="{{ optional($member->spouse_birthday)->format('d-m-Y') ?? old('spouse_birthday')}}" placeholder="{{ __
                            ('Enter value') }}" autocomplete="off">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_identity') ? 'has-danger' : ''}}">
                                <label for="txt_spouse_identity">{{ $member->label('spouse_identity') }}</label>
                                <input disabled class="form-control identity-number" name="spouse_identity" type="text" id="txt_spouse_identity" value="{{ $member->spouse_identity ?? old('spouse_identity')}}" placeholder="{{ __('Enter value') }}"
                                       autocomplete="off">
                                <span class="m-form__help"></span>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_identity_address') ? 'has-danger' : ''}}">
                                <label for="txt_spouse_identity_address">{{ $member->label('spouse_identity_address') }}</label>
                                <input disabled class="form-control" name="name" type="text" id="txt_spouse_identity_address" value="{{ optional($member->spouseIdentityProvince)->name }}">
                                <span class="m-form__help"></span>
                                {!! $errors->first('spouse_identity_address', '<div class="form-control-feedback">:message</div>') !!}
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('spouse_identity_date') ? 'has-danger' : ''}}">
                                <label for="txt_spouse_identity_date">{{ $member->label('spouse_identity_date') }}</label>
                                <input disabled class="form-control" name="spouse_identity_date" type="text" id="txt_spouse_identity_date" value="{{ optional($member->spouse_identity_date)->format('d-m-Y')}}">
                                <span class="m-form__help"></span>
                                {!! $errors->first('spouse_identity_date', '<div class="form-control-feedback">:message</div>') !!}
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub">
                            <label for="txt_address">Địa chỉ liên lạc</label>
                            <input disabled class="form-control" name="address" type="text" id="txt_address" value="{{ $member->address}}">
                            <span class="m-form__help"></span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub">
                            <label for="txt_address">Địa chỉ thường trú</label>
                            <input disabled class="form-control" name="address" type="text" id="txt_address" value="{{ $member->temp_address}}">
                            <span class="m-form__help"></span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('state') ? 'has-danger' : ''}}">
                            <label for="txt_city">Chi nhánh</label>
                            <input disabled class="form-control" type="text" id="txt_city" value="{{ optional($member->province)->name}}">
                            <span class="m-form__help"></span>
                        </div>
                    </div>
                    {{--END MEMBER INFO--}}

                    {{--CONTRACT--}}
                    <div class="form-group m-form__group row">
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('contract_no') ? 'has-danger' : ''}}">
                            <label for="txt_contract_no">{{ $contract->label('contract_no') }}</label>
                            <input disabled class="form-control numeral" name="contract_no" type="text" id="txt_contract_no" value="{{ $contract->contract_no ?? old('contract_no')}}">
                            <span class="m-form__help"></span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('signed_date') ? 'has-danger' : ''}}">
                            <label for="txt_signed_date">{{ $contract->label('signed_date') }}</label>
                            <input disabled class="form-control" name="signed_date" type="text" id="txt_signed_date" value="{{ $contract->signed_date->format('d-m-Y') ?? old('signed_date')}}">
                            <span class="m-form__help"></span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('amount') ? 'has-danger' : ''}}">
                            <label for="txt_amount">{{ $contract->label('amount') }}</label>
                            <input disabled class="form-control numeric" name="amount" type="text" id="txt_amount" value="{{ number_format($contract->amount)}}">
                            <span class="m-form__help"></span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('membership') ? 'has-danger' : ''}}">
                            <label for="txt_membership">{{ $contract->label('membership') }}</label>
                            <input disabled class="form-control" name="amount" type="text" id="txt_membership" value="{{ $contract->membership_text}}">
                            <span class="m-form__help"></span>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('room_type') ? 'has-danger' : ''}}">
                            <label for="txt_room_type">{{ $contract->label('room_type') }}</label>
                            <input disabled class="form-control" name="amount" type="text" id="txt_room_type" value="{{ $contract->room_type_text}}">
                            <span class="m-form__help"></span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('limit') ? 'has-danger' : ''}}">
                            <label for="txt_limit">{{ $contract->label('limit') }}</label>
                            <input disabled class="form-control" name="amount" type="text" id="txt_limit" value="{{ $contract->limit_text}}">
                            <span class="m-form__help"></span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('start_date') ? 'has-danger' : ''}}">
                            <label for="txt_start_date">{{ $contract->label('start_date') }}</label>
                            <input disabled class="form-control" name="start_date" type="text" id="txt_start_date" value="{{ $contract->start_date->format('d-m-Y') }}">
                            <span class="m-form__help"></span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('end_time') ? 'has-danger' : ''}}">
                            <label for="txt_end_time">{{ $contract->label('end_time') }}</label>
                            <input disabled class="form-control" name="end_time" type="text" id="txt_end_time" value="{{ $contract->end_time}}">
                            <span class="m-form__help"></span>
                        </div>
                    </div>
                    {{--END CONTRACT--}}

                    {{--PAYMENT_DETAIL--}}
                    <div class="form-group m-form__group row">
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('payment_method') ? 'has-danger' : ''}}">
                            <label for="select_payment_method">{{ $paymentCost->label('payment_method') }}</label>
                            <input class="form-control" name="cost" type="text" id="txt_payment_cost" value="{{ $firstPaymentDetail->payment_cost->payment_method_text}}" disabled placeholder="{{ __('Enter value') }}" autocomplete="off">
                            <span class="m-form__help"></span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('bank_name') ? 'has-danger' : ''}}">
                            <label for="select_bank">{{ $paymentCost->label('bank_name') }}</label>
                            <input class="form-control" name="cost" type="text" id="txt_bank_name" value="{{ $firstPaymentDetail->payment_cost->bank_name ?? old('bank_name')}}" disabled placeholder="{{ __('Enter value') }}" autocomplete="off">
                            <span class="m-form__help"></span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('cost') ? 'has-danger' : ''}}">
                            <label for="txt_cost">{{ $paymentCost->label('cost') }}</label>
                            <input disabled class="form-control" name="cost" type="text" id="txt_cost" value="{{ $firstPaymentDetail->payment_cost->cost ?? old('cost')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                            <span class="m-form__help"></span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub {{ $errors->has('bank_no') ? 'has-danger' : ''}}">
                            <label for="txt_bank_no">{{ $paymentCost->label('bank_no') }}</label>
                            <input disabled class="form-control" name="bank_no" type="text" id="txt_bank_no" value="{{ $firstPaymentDetail->bank_no ?? old('bank_no')}}" placeholder="{{ __('Enter value') }}" autocomplete="off">
                            <span class="m-form__help"></span>
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub">
                            <label for="txt_year_cost">{{ $contract->label('year_cost') }}</label>
                            <input disabled class="form-control" name="year_cost" type="text" id="txt_year_cost" value="{{ number_format($contract->year_cost)  }}">
                            <span class="m-form__help"></span>
                        </div>
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3 m-form__group-sub">
                            <label for="txt_payment_time">Số lần thanh toán</label>
                            <input class="form-control" name="num_of_payment" type="text" id="txt_payment_time" value="{{ $contract->num_of_payment }}" required placeholder="{{ __('Enter value') }}" autocomplete="off" disabled>
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
                                            <td>{{ $paymentDetail->pay_date->format('d-m-Y') }}</td>
                                            <td>{{ number_format($paymentDetail->total_paid_deal) }}</td>
                                        </tr>
                                    @endforeach
                                @endisset
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </form>
            <div class="m-form m-form--fit m-form--label-align-right">
                <div class="m-portlet__foot m-portlet__foot--fit m-portlet__foot-no-border">
                    <div class="m-form__actions m-form__actions--right">
                        @if (can('update-contract'))
                            <a href="{{ route('contracts.edit', $contract) }}" class="btn btn-brand m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-edit"></i><span>@lang('Edit')</span></span></a>
                        @endif
                        <a href="{{ route('contracts.index') }}" class="btn btn-secondary m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-arrow-left"></i><span>@lang('Back')</span></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
