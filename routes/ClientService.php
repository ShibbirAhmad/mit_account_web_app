<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'admin',
    'namespace' => 'Admin',
    //'prefix' => 'backend/'
], function () {


    //service inventory route is here
    Route::get('api/services','ServiceController@services');
    Route::post('api/service/add','ServiceController@addService');
    Route::get('api/service/get/{id}','ServiceController@serviceItem');
    Route::post('api/service/update/{id}','ServiceController@updateService');
    Route::get('api/service/active/{id}','ServiceController@activeService');
    Route::get('api/service/inactive/{id}','ServiceController@inactiveService');
    //clients
    Route::get('api/service/clients','ServiceController@serviceClients');
    Route::post('api/service/client/add','ServiceController@addClient');
    Route::post('api/service/client/info/edit/{id}','ServiceController@updateClient');
    Route::get('api/get/service/client/{id}','ServiceController@getClient');
    Route::get('api/service/client/search/{phone}','ServiceController@searchClient');
    //package and bil
    Route::post('/api/service/client/package/add','ServiceController@addClientServicePackage');
    Route::get('api/service/details/and/clients/{service_id}','ServiceController@serviceAndClients')->where('service_id','[0-9]+');
    //service client payment
    Route::post('api/store/client/payment','ServiceController@storeClientPayment');
    Route::get('api/client/service/details/and/payment/{client_id}','ServiceController@clientServiceAndPayment');
    //cron job api for due message to clients
    Route::get('api/send/contractual/service/due/notification','ServiceController@sendMessageToContractualDueClients');
    Route::get('api/send/monthly/service/due/notification','ServiceController@sendMessageToMonthlyDueClients');

    Route::get('api/get/monthly/records/{id}','ServiceController@getMonthlyRecord');
    Route::post('api/service/monthly/record/add','ServiceController@storeMitMonthlyRecord');






});
