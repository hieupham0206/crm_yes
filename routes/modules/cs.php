<?php

Route::get('/event-data/get-bank-from-method', 'PaymentCostsController@getBank')->name('payment_costs.get_bank');
Route::post('/contracts/change-state/{contract}', 'ContractsController@changeState')->name('contracts.change_state');


Route::get('/contracts/export/excel', 'ContractsController@exportExcel')->name('contracts.export_excel');