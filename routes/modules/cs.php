<?php

Route::get('/event-data/get-bank-from-method', 'PaymentCostsController@getBank')->name('payment_costs.get_bank');
Route::post('/contracts/change-state/{contract}', 'ContractsController@changeState')->name('contracts.change_state');


Route::get('/contracts/export/excel', 'ContractsController@exportExcel')->name('contracts.export_excel');
Route::get('/payment_details/export/excel', 'PaymentDetailsController@exportExcel')->name('payment_details.export_excel');
Route::get('/event_datas/export/excel', 'EventDataCsController@exportExcel')->name('event_data_cs.export_excel');