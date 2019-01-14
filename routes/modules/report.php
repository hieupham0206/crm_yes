<?php

Route::get('/daily-teles/', 'DailyTeleReportsController@index')->name('daily_teles.index');
Route::post('/daily-teles/lists/table', 'DailyTeleReportsController@table')->name('daily_teles.table');