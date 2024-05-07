<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('contact', 'ContactCrudController');
    // Route::crud('location', 'LocationCrudController');
    Route::crud('setting', 'SettingCrudController');
    Route::crud('system-notification', 'SystemNotificationCrudController');
    Route::get('makeuser/{id}', 'ContactCrudController@convertToUser');
    Route::crud('roles', 'RolesCrudController');
    Route::get('notifications', 'AdminController@loadAdminPanel')->name('admin.panel');
    Route::get('sendemail/{id}', 'SystemNotificationCrudController@sendEmail');


}); // this should be the absolute last line of this file