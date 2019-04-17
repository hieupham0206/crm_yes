<?php

Route::get('/leads/import/form', 'LeadsController@formImport')->name('leads.form_import');
Route::get('/leads/export/excel', 'LeadsController@exportExcel')->name('leads.export_excel');
Route::get('/leads/check-lead/new', 'LeadsController@checkAvailableNewLead')->name('leads.check_available_new');
Route::post('/leads/import/', 'LeadsController@import')->name('leads.import');

Route::get('/leads/change-state/form/{lead?}', 'LeadsController@formChangeState')->name('leads.form_change_state');
Route::post('/leads/chage-state/{lead?}', 'LeadsController@changeState')->name('leads.change_state');
Route::post('/leads/appointment/update/{appointment}', 'LeadsController@editAppointmentTime')->name('leads.edit_appointment_time');
Route::post('/leads/callback/update/{callback}', 'LeadsController@editCallbackTime')->name('leads.edit_callback_time');

Route::get('/provinces/lists/table', 'LeadsController@provinces')->name('leads.provinces.table');

Route::get('/leads/reception/form-new-lead', 'LeadsController@formNewLead')->name('leads.form_new_lead');

Route::post('/leads/resend-email/{lead}/{appointment}', 'LeadsController@resendEmail')->name('leads.resend_email');
Route::post('/leads/save-history-call/{lead}', 'LeadsController@saveHistoryCall')->name('leads.save_history_call');

Route::get('/appointments/export/excel', 'AppointmentsController@exportExcel')->name('appointments.export_excel');
Route::get('/callbacks/export/excel', 'CallbacksController@exportExcel')->name('callbacks.export_excel');
Route::get('/history_calls/export/excel', 'HistoryCallsController@exportExcel')->name('history_calls.export_excel');

Route::get('/appointments/import/form', 'AppointmentsController@formImport')->name('appointments.form_import');
Route::post('/appointments/import/', 'AppointmentsController@import')->name('appointments.import');

Route::post('/leads/console/pull-call-cache/{lead}', 'LeadsController@putCallCache')->name('leads.put_call_cache');


Route::get('/event-data/', 'EventDataRecepsController@index')->name('event_data_receps.index');
Route::get('/event-data/export/excel', 'EventDataRecepsController@exportExcel')->name('event_data_receps.export_excel');

Route::post('/event-data/lists/table', 'EventDataRecepsController@table')->name('event_data_receps.table');