<?php

use Sam\QrPayment\Http\Controllers\QrPaymentController;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Sam\QrPayment\Http\Controllers', 'middleware' => ['web', 'core']], function () {
    Route::group(['prefix' => 'payment/qr-payment', 'as' => 'qr-payment.'], function () {
        Route::get('instructions/{token}', [
            'as' => 'instructions',
            'uses' => 'QrPaymentController@instructions',
        ]);
        
        Route::post('confirm/{token}', [
            'as' => 'confirm',
            'uses' => 'QrPaymentController@confirm',
        ]);
    });
});
