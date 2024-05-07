<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NotificationSendController;
use App\Http\Controllers\HomeController;

Route::get('/', function () {
    return view('welcome');
});


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'],function(){
    Route::post('/store-token', [NotificationSendController::class, 'updateDeviceToken'])->name('store.token');
    Route::post('/send-web-notification', [NotificationSendController::class, 'sendNotification'])->name('send.web-notification');
      // Ensure only user ID 1 can send notifications
    //   Route::post('/send-web-notification', function (Request $request) {
    //     if (auth()->id() === 1) {
    //         return app(NotificationSendController::class)->sendNotification($request);
    //     } else {
    //         abort(403); // Access forbidden
    //     }
    // })->name('send.web-notification');
});