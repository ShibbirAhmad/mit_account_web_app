<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('auth')->group(function () {
    
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');


    Route::post('reset/code', 'AuthController@resetCode');

    Route::post('verify/code', 'AuthController@codeVerify');
    

});


Route::middleware('auth:api-reseller')->prefix('auth')->group(function (){
    Route::post('/logout', 'AuthController@logout');
    Route::get('/user', function (Request $request) {
        return response()->json($request->user('api-reseller'));
    });
});


