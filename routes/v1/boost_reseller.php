<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::namespace('BoostReseller')->prefix('client')->name('client.')->group(function () {
    
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('user', 'AuthController@user');

});



// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
