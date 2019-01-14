<?php
Auth::routes();

Route::get('/otp/{phone}/{username}', 'Auth\LoginController@formOtp')->name('otp');
Route::post('/resend-otp/{phone}/{username}', 'Auth\LoginController@resendOtp')->middleware('throttle:1,5')->name('resend_otp');
Route::post('/login-otp', 'Auth\LoginController@loginOtp')->name('login_otp');

//home route
Route::get('/', 'HomeController@index')->middleware(['auth', 'active'])->name('home');
Route::get('/reception', 'HomeController@reception')->middleware(['auth', 'active'])->name('reception');
Route::get('/tele-marketer-console', 'HomeController@teleMarketerConsole')->middleware(['auth', 'active'])->name('tele_console');

Route::get('/dashboard-monitor', 'DashboardController@index')->name('dashboard_monitor_sale');
Route::get('/test-api', function() {
//    dd(sys_get_temp_dir(), ini_get('upload_tmp_dir'));
//    dd(
//        file_exists(ini_get('upload_tmp_dir')),
//        is_writable(ini_get('upload_tmp_dir')),
//        file_exists(ini_get('upload_tmp_dir')));

//    $fpt = new \App\TechAPI\FptSms();
//    $fpt->testApi();
});

Route::get('/monitor-sale', 'HomeController@monitorSale')->middleware(['auth', 'active'])->name('monitor_sale');
Route::get('/monitor-sale/load-section-motitor', 'HomeController@sectionMonitorSale')->name('monitor_sale.section_monitor');
Route::get('/monitor-sale/form/user-detail/{user?}', 'HomeController@formUserDetail')->name('monitor_sale.form_detail');

//language route
Route::get('/js/lang.js', 'HomeController@lang')->name('lang');

//QUICKSEARCH
Route::get('/quicksearch', 'HomeController@quickSearch');

$routeArr = getRouteConfig();
foreach ($routeArr as $key => $routes) {
    $namespace = ucfirst($key);
    $prefix    = $key;

    // ROUTE CHUNG
    Route::namespace($namespace)->prefix($prefix)->group(function () use ($routes) {
        foreach ($routes as $route) {
            $ucTable         = ucfirst(studly_case($route));
            $ucTableSingular = str_singular($ucTable);
            $varRouteName    = lcfirst(studly_case($route));

            if (strpos($route, 'event_data_cs') !== false) {
                $ucTableSingular = 'EventDataCs';
            }

            if (class_exists("App\\Models\\{$ucTableSingular}")) {
                Route::resource($route, "{$ucTable}Controller");
                Route::get("/{$route}/lists/list", "{$ucTable}Controller@{$varRouteName}")->name("{$route}.list");
                Route::post("/{$route}/lists/table", "{$ucTable}Controller@table")->name("{$route}.table");

                Route::delete("/{$route}/action/deletes", "{$ucTable}Controller@destroys")->name("{$route}.destroys");
                Route::post("/{$route}/action/edits", "{$ucTable}Controller@edits")->name("{$route}.edits");
                Route::put("/{$route}/action/updates", "{$ucTable}Controller@updates")->name("{$route}.updates");
            }
        }
    });
}

//ROUTE CHI TIẾT
Route::namespace('Admin')->prefix('admin')->group(base_path('routes/modules/admin.php'));
Route::namespace('Business')->prefix('business')->group(base_path('routes/modules/business.php'));
Route::namespace('Report')->prefix('report')->group(base_path('routes/modules/report.php'));
Route::namespace('Cs')->prefix('report')->group(base_path('routes/modules/cs.php'));