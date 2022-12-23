<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('shipNotification/{order}','Api\ShipmentNotificationController@index');
Route::post('shipNotificationDesk/{order}','Api\ShipmentNotificationController@shipNotificationDesk');

Route::middleware(['checkapiheader'])->group(function () {
    Route::get('validUser', 'Api\UserController@index');
    Route::get('getOrder', 'Api\OrdersController@getOrder');
    Route::post('neworder','Api\OrdersController@create');
    Route::post('cancelOrder','Api\OrdersController@cancel');
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
