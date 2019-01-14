<form id="import_leads_form" class="m-form m-form--state" method="post" action="{{ route('leads.import') }}" enctype="multipart/form-data">
    <div class="modal-header">
        <h5 class="modal-title">@lang('Import')</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        @csrf
        <div class="m-portlet__body">
            <div role="alert" class="alert alert-dismissible fade show m-alert m-alert--air alert-danger" style="display: none;">
                <button type="button" data-dismiss="alert" aria-label="Close" class="close"></button>
                <strong></strong>
            </div>
            <div class="form-group row">
                {{--<div class="col-lg-12">--}}
                    {{--<label class="m-checkbox">--}}
                        {{--<input type="checkbox" name="is_private" value="-1" checked> Public--}}
                        {{--<span></span>--}}
                    {{--</label>--}}
                {{--</div>--}}
                <div class="col-lg-12">
                    <div class="fileinput fileinput-new">
                        <div class="input-group">
                            <div class="form-control" data-trigger="fileinput"><i class="fa fa-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                            <div class="input-group-append">
                                <span class="btn btn-default btn-file">
                                    <span class="fileinput-new">@lang('Select file')</span>
                                    <span class="fileinput-exists">@lang('Change')</span>
                                    <input type="file" name="file_import" accept=".xlsx, .xls">
                                </span>
                                <button class="btn btn-brand fileinput-exists">{{ __('Import') }}</button>
                                <a href="#" class="btn btn-danger fileinput-exists" data-dismiss="fileinput">@lang('Delete')</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 form-group">
                    <label for="select_user_id">Nhân viên</label>
                    <select name="user_id" id="select_user_id" data-url="{{ route('users.list') }}" class="select2-ajax" data-column="name">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a href="{{ asset('files/Lead_Data.xlsx') }}" download>
            <button type="button" class="btn btn-brand m-btn--custom m-btn--icon" id="download_file_sample">
                <span><i class="fa fa-download"></i>
                    <span>@lang('Download file sample')</span>
                </span>
            </button>
        </a>
        <button type="button" class="btn btn-secondary m-btn--custom m-btn--icon" data-dismiss="modal">
            <span>
                <i class="fa fa-ban"></i>
                <span>@lang('Close')</span>
            </span>
        </button>
    </div>
</form>