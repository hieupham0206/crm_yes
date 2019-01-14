@php /** @var \App\Models\EventData $eventData */
$breadcrumbs = ['breadcrumb' => 'event_datas.show', 'model' => $eventData];
@endphp

@extends("$layout.app")

@push('scripts')

@endpush

@section('title', __('action.View Model', ['model' => $eventData->classLabel(true)]))

@section('content')
    <div class="m-content">
        <div class="m-portlet">
            <div class="m-portlet__body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                                                <th> {{ $eventData->label('name') }} </th>
                                                <td> {{ $eventData->name }} </td>
                                              </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="m-form m-form--fit m-form--label-align-right">
                <div class="m-portlet__foot m-portlet__foot--fit m-portlet__foot-no-border">
                    <div class="m-form__actions m-form__actions--right">
                        @if (can('update-eventData'))
                            <a href="{{ route('event_datas.edit', $eventData) }}" class="btn btn-brand m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-edit"></i><span>@lang('Edit')</span></span></a>
                        @endif
                        <a href="{{ route('event_datas.index') }}" class="btn btn-secondary m-btn m-btn--icon m-btn--custom"><span><i class="fa fa-arrow-left"></i><span>@lang('Back')</span></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
