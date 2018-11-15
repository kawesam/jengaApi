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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/generate-token','HooksController@generateToken');

Route::post('/account/balance','HooksController@checkAccountBalance');

Route::post('/account/mini/statement','HooksController@generateMiniStatement');

Route::post('/account/inquiry','HooksController@accountInquiry');

//Money movement
Route::post('/move/money/within','HooksController@moveMoneyWithinEquity');
Route::post('/move/mobile/money','HooksController@moveMoneyToMobile');
Route::post('/move/rtgs/money','HooksController@moveMoneyViaRtgs');
Route::post('/move/swift/money','HooksController@moveMoneyViaSwift');
Route::post('/move/eft/money','HooksController@moveMoneyViaEft');
Route::post('/move/pesalink/money/bank','HooksController@moveMoneyViaPesaLinkToBank');
