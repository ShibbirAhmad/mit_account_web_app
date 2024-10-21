<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




Route::prefix('v1/client')->middleware('throttle:10,1')->group(function () {
    
    Route::post('login', 'AuthController@login');
    Route::post('logout', 'AuthController@logout');
    Route::post('user', 'AuthController@user');

});



// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
