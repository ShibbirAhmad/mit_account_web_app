<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'admin',
    'namespace' => 'Admin',
    //'prefix' => 'backend/'
], function () {



    //company boost and reseller route is here
    Route::get('api/boost/agency/list','BoostAgencyController@agency_list');
    Route::get('api/boost/reseller/just/list','BoostAgencyController@ResellersList');
    Route::get('api/boost/agency/resellers/list','BoostAgencyController@agency_reseller_list');
    Route::post('api/boost/agency/reseller/add','BoostAgencyController@storeBoostReseller');
    Route::post('api/store/boost/reseller/dollar','BoostAgencyController@storeBoostResellerDollar');
    Route::post('api/store/boost/reseller/payment','BoostAgencyController@storeBoostResellerPayment');
    Route::post('api/boost/agency/add','BoostAgencyController@store');
    Route::post('api/pay/boost/agency/payment','BoostAgencyController@storeAgencyPayment');
    Route::get('api/boost/reseller/transaction/details/{id}','BoostAgencyController@resellerTransactions');
    Route::get('api/boost/agency/payment/details/{id}','BoostAgencyController@boostAgencyPayments');
    Route::get('api/get/boost/agency/reseller/{id}','BoostAgencyController@getBoostReseller');
    Route::post('api/get/boost/agency/reseller/edit/{id}','BoostAgencyController@updateBoostReseller');
    Route::post('api/boost/reseller/advertise/account/add','BoostAgencyController@boostResellerAccountAdd');
    Route::post('api/dollar/transfer/from/reseller','BoostAgencyController@DollarTransfer');
    Route::get('api/download/pdf/boost/reseller/ad-account/{id}','BoostAgencyController@downloadAccountPdf');
    Route::get('api/download/pdf/boost/reseller/account/all/{id}','BoostAgencyController@downloadAllAccountPdf');
    Route::get('api/download/pdf/boost/reseller/account/date-wise/{start_date}/{end_date}/{id}','BoostAgencyController@downloadAccountPdfWithFilter');
    Route::get('api/download/pdf/boost/agency/resellers/date-wise/{start_date}/{end_date}/{id}','BoostAgencyController@downloadPdfAllReseller');




});
