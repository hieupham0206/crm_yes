<?php

Route::get('/daily-teles/', 'DailyTeleReportsController@index')->name('daily_teles.index');
Route::post('/daily-teles/lists/table', 'DailyTeleReportsController@table')->name('daily_teles.table');
Route::get('/daily-teles/export/excel', 'DailyTeleReportsController@exportExcel')->name('daily_teles.export_excel');

Route::get('/daily-sales/', 'DailySaleReportsController@index')->name('daily_sales.index');
Route::post('/daily-sales/lists/table', 'DailySaleReportsController@table')->name('daily_sales.table');