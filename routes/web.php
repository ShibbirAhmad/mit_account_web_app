<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

Route::get('reboot', function () {
Artisan::call('cache:clear');
Artisan::call('config:clear');
Artisan::call('route:clear');
Artisan::call('config:cache');
     dd("Ready to Start.");
});

Route::get('/', function(){
// $exitCode = Artisan::call('storage:link');
return view('frontend.master');

})->name('welcome');


//others route
Route::get('_public/others', 'Admin\OthersController@others')->middleware('auth');

//:::::start the backend route::::::
Route::group([
    'middleware' => 'admin',
    'namespace' => 'Admin',
    //'prefix' => 'backend/'
], function () {

     //start dashboard route
    Route::get('account/converter', 'AccountController@AccountConverter');

    Route::get('api/order/stock/sell/analysis/and/others', 'DashboardController@OrderCreateAnalysis');
    Route::get('api/every/accounts/department', 'DashboardController@DepartmentWiseAccounts');
    Route::get('dashboard/welcome', 'DashboardController@dashboard');
    //started user
    Route::get('list/user/all','UserController@getUser');
    Route::get('user/search/{data}','UserController@search_user');
    Route::get('api/export/users','UserController@export_users');
    Route::get('deactive/user/{id}','UserController@deActiveUser');
    Route::get('active/user/{id}','UserController@activeUser');
    //end user

   //send message url is here
   Route::post('api/send/message/to/customer','MessageController@send_message');

   //route for display subscriber
   Route::get('api/display/subscribers','OthersController@subscribers');
   Route::get('api/subscriber/search/{data}','OthersController@search_subscribers');
   Route::get('api/subscriber/remove/{id}','OthersController@remove_subscriber');
   Route::get('api/subscriber/exports','OthersController@export_subscriber');

   //route for contact user message display
   Route::get('api/display/contacts','OthersController@contact_message');
   Route::get('api/get/contacted/customer/{id}','OthersController@get_contacted_customer');
   Route::get('api/contacted/customer/search/{data}','OthersController@search_contacted_customer');
   Route::get('api/reply/message/to/contacted/customer/{to}/{subject}/{message}','Contact_replyController@reply_contacted_customer');


  // start team route here
   Route::get('team/members/list','TeamController@index');
   Route::post('team/members/add','TeamController@addTeamMember');
   Route::get('team/members/edit/{id}','TeamController@getEditTeamMember');
   Route::post('team/members/update/{id}','TeamController@updateTeamMember');
   Route::get('team/members/trash/{id}','TeamController@destroyTeamMember');
   Route::get('team/members/active/{id}','TeamController@activeTeamMember');
   Route::get('team/members/deactive/{id}','TeamController@deactiveTeamMember');
   Route::get('api/team/members/search/{data}','TeamController@search_team_member');

   Route::get('api/member/salary/list/{id}','TeamController@salary');
   Route::get('api/member/salary/list/{id}/{month}','TeamController@salaryDetails');
   Route::get('/api/employee/salary/paid','TeamController@paidSalary');


   // end team route here


  //start account  route here....
  Route::get('api/account/purpose/list','AccountController@get_purpose_list');
  Route::post('api/account/purpose/add','AccountController@add_purpose');
  Route::get('api/account/purpose/edit/{id}','AccountController@get_edit_purpose');
  Route::post('api/account/purpose/update/{id}','AccountController@update_purpose');
  // end  account route here


    //start merchant route
    Route::get('api/merchant/list','MerchantController@index');
    Route::post('api/merchant/add','MerchantController@addMerchant');
    Route::get('api/merchant/edit/{id}','MerchantController@getEditMerchant');
    Route::post('api/merchant/update/{id}','MerchantController@updateMerchant');
    Route::get('api/merchant/trash/{id}','MerchantController@destroyMerchant');
    Route::get('api/merchant/active/{id}','MerchantController@activeMerchant');
    Route::get('api/merchant/deactive/{id}','MerchantController@deactiveMerchant');
    Route::get('api/search/merchant/{data}','MerchantController@searchMerchant');
    Route::get('api/admin/to/merchant/{id}','MerchantController@adminAccessMerchant');
    Route::get('api/export/merchants','MerchantController@exportMerchant');
    // end merchant route here

  //start carrier controller here

  Route::get('carrier/list','CarrierController@index');
  Route::post('carrier/add','CarrierController@add_carrier');
  Route::get('carrier/edit/{id}','CarrierController@getEdit_carrier');
  Route::post('carrier/update/{id}','CarrierController@update_carrier');
  Route::get('carrier/trash/{id}','CarrierController@destroy_carrier');
  Route::get('carrier/active/{id}','CarrierController@active_carrier');
  Route::get('carrier/deactive/{id}','CarrierController@deactive_carrier');
  Route::get('api/applied/applier/job/{id}','CarrierController@job_applied');
  Route::get('api/download/user/resume/{id}','CarrierController@download_applied_user_resume');

  // end carrier controller here


    //started customer
    Route::get('/list/customer','CustomerController@getCustomer');
    Route::get('/search/customer/{data}','CustomerController@searchCustomer');
    Route::get('api/export/customers','CustomerController@export_customers');
    Route::get('api/export/retail/customers','CustomerController@export_retail_customers');
    Route::get('api/customers/export/wholesale','CustomerController@export_wholesale_customers');
    Route::get('api/export//customers/officesale','CustomerController@export_officesale_customers');
    Route::get('api/export/due/customer', 'CustomerController@exportDueCustomer');
    //end customer


    //start the category route
    Route::post('/category/add', 'CategoryController@store');
    Route::get('list/category', 'CategoryController@index');
    Route::get('selected/category/{id}', 'CategoryController@selected');
    Route::get('unselected/category/{id}', 'CategoryController@unselected');
    Route::get('active/category/{id}', 'CategoryController@active');
    Route::get('deActive/category/{id}', 'CategoryController@deActive');
    Route::get('search/category/{search}', 'CategoryController@search');
    Route::get('edit/category/{id}', 'CategoryController@edit');
    Route::post('update/category/{id}', 'CategoryController@update');


    //depended route
    Route::get('/category/wise/subCategory/{id}', 'CategoryController@categoryWiseSubCategory');
    Route::get('/subCategory/wise/subSubCategory/{id}', 'SubCategoryController@subCategoryWiseSubCategory');
    Route::get('/attribute/wise/variant/{id}', 'AttributeAndVariantController@attributeWiseVariant');


    //start the sub-category route
    Route::get('list/subCategory', 'SubCategoryController@index');
    Route::post('/subCategory/add', 'SubCategoryController@store');
    Route::get('active/subCategory/{id}', 'SubCategoryController@active');
    Route::get('deActive/subCategory/{id}', 'SubCategoryController@deActive');
    Route::get('search/subCategory/{search}', 'SubCategoryController@search');
    Route::get('edit/subCategory/{id}', 'SubCategoryController@edit');
    Route::post('update/subCategory/{id}', 'SubCategoryController@update');
    //apply discount
    Route::post('api/sub-category/discount/add/{id}', 'SubCategoryController@addDiscount');



    //start the sub-sub category route
    Route::get('/list/subSubCategory', 'SubSubCategoryController@index');
    Route::post('/subSubCategory/add', 'SubSubCategoryController@store');
    Route::get('active/subSubCategory/{id}', 'SubSubCategoryController@active');
    Route::get('deActive/subSubCategory/{id}', 'SubSubCategoryController@deActive');
    Route::get('search/subSubCategory/{search}', 'SubSubCategoryController@search');
    Route::get('edit/subSubCategory/{id}', 'SubSubCategoryController@edit');
    Route::post('update/subSubCategory/{id}', 'SubSubCategoryController@update');
    //apply discount
    Route::post('api/sub-sub-category/discount/add/{id}', 'SubSubCategoryController@addDiscount');



     //start the slider route
    Route::get('/list/slider', 'SliderController@index');
    Route::post('/slider/add', 'SliderController@store');
    Route::get('/get/edit/slider/{id}', 'SliderController@getEditSlider');
    Route::post('/slider/update/{id}', 'SliderController@updateSlider');
    Route::get('active/slider/{id}', 'SliderController@active');
    Route::get('deActive/slider/{id}', 'SliderController@deActive');
    Route::get('delete/slider/{id}', 'SliderController@destroy');


    //start the category slider route
    Route::get('api/category/list/slider', 'SliderController@category_slider_index');
    Route::post('api/category/slider/add', 'SliderController@category_slider_store');
    Route::get('api/category/get/edit/slider/{id}', 'SliderController@get_category_slider_edit');
    Route::post('api/category/slider/update/{id}', 'SliderController@update_category_slider');
    Route::get('api/category/active/slider/{id}', 'SliderController@active_category_slider');
    Route::get('api/category/deActive/slider/{id}', 'SliderController@deActive_category_slider');
    Route::get('api/category/delete/slider/{id}', 'SliderController@destroy_category_slider');

    //start teh attribute route route
    Route::get('/list/attribute', 'AttributeAndVariantController@index');
    Route::post('/attribute/add', 'AttributeAndVariantController@store');
    Route::get('active/attribute/{id}', 'AttributeAndVariantController@active');
    Route::get('deActive/attribute/{id}', 'AttributeAndVariantController@deActive');
    Route::get('delete/attribute/{id}', 'AttributeAndVariantController@destroy');


    //start the variant route route
    Route::get('/list/variant', 'AttributeAndVariantController@variant');
    Route::post('/variant/add', 'AttributeAndVariantController@storeVariant');
    Route::get('active/variant/{id}', 'AttributeAndVariantController@activeVariant');
    Route::get('deActive/variant/{id}', 'AttributeAndVariantController@deActiveVariant');


    //start the merchant route
    Route::get('/list/supplier', 'SupplierController@index');
    Route::post('supplier/add', 'SupplierController@store');
    Route::get('/active/supplier/{id}', 'SupplierController@active');
    Route::get('/deActive/supplier/{id}', 'SupplierController@deActive');
    Route::get('/edit/supplier/{id}', 'SupplierController@edit');
    Route::post('/update/supplier/{id}', 'SupplierController@update');
    Route::get('api/search/supplier/{data}', 'SupplierController@search_supplier');
    Route::get('/api/suplier/amount/{id}', 'SupplierController@supplierAmountList');
    Route::get('/api/pdf/suplier/amount/{id}', 'SupplierController@pdfSupplierAmount');
    Route::get('/api/pdf/suplier/purchase/{id}', 'SupplierController@pdfSupplierPurchase');
    Route::get('api/supplier/list', 'SupplierController@supplierList');
    Route::get('api/export/supplier', 'SupplierController@export_supplier');


    //start the city route
    Route::get('/list/city', 'CityController@index');
    Route::post('city/add', 'CityController@store');
    Route::get('/active/city/{id}', 'CityController@active');
    Route::get('/deActive/city/{id}', 'CityController@deActive');
    Route::get('/edit/city/{id}', 'CityController@edit');
    Route::get('api/search/city/{data}', 'CityController@search_city');
    Route::post('/update/city/{id}', 'CityController@update');


     //start here the sub city route
    Route::get('api/list/sub/city','SubCityController@get_sub_city_list');
    Route::post('api/sub/city/add','SubCityController@store_sub_city');
    Route::get('api/active/sub/city/{id}','SubCityController@active_city');
    Route::get('api/deActive/sub/city/{id}','SubCityController@de_active_city');
    Route::get('api/edit/sub/city/{id}','SubCityController@get_edit_item');
    Route::get('api/search/sub/city/{data}','SubCityController@search_sub_city');
    Route::post('api/update/sub/city/{id}','SubCityController@update_sub_city');
     Route::get('city/wise/sub/city/{id}','SubCityController@cityWiseSubCity');


    //start the offer route
    Route::post('offer/add', 'OfferController@store');
    Route::get('api/edit/offer/item/get/{id}', 'OfferController@get_edit_offer_item');
    Route::post('api/offer/edit/{id}', 'OfferController@edit_offer_item');
    Route::get('/list/offer', 'OfferController@index');
    Route::get('/active/offer/{id}', 'OfferController@active');
    Route::get('/deActive/offer/{id}', 'OfferController@deActive');
    Route::get('/delete/offer/{id}', 'OfferController@destroy');


    //start the courier route
    Route::get('/list/courier', 'CourierController@index');
    Route::post('courier/add', 'CourierController@store');
    Route::get('/active/courier/{id}', 'CourierController@active');
    Route::get('/deActive/courier/{id}', 'CourierController@deActive');
    Route::get('/edit/courier/{id}', 'CourierController@edit');
    Route::get('api/search/courier/{data}', 'CourierController@search_courier');
    Route::post('/update/courier/{id}', 'CourierController@update');


    //start the comment route
    Route::get('/list/comment', 'CommentController@index');
    Route::post('comment/add', 'CommentController@store');
    Route::get('/active/comment/{id}', 'CommentController@active');
    Route::get('/deActive/comment/{id}', 'CommentController@deActive');
    Route::get('/edit/comment/{id}', 'CommentController@edit');
    Route::post('/update/comment/{id}', 'CommentController@update');
    Route::get('/destroy/comment/{id}', 'CommentController@destroy');



     //start the factory route
     Route::get('api/list/factory', 'FactoryController@index');
     Route::post('api/factory/add', 'FactoryController@store');
     Route::get('api/active/factory/{id}', 'FactoryController@active');
     Route::get('api/deActive/factory/{id}', 'FactoryController@deActive');
     Route::get('api/edit/factory/{id}', 'FactoryController@edit');
     Route::post('api/update/factory/{id}', 'FactoryController@update');


    //start the product route
    Route::get('/list/product', 'ProductController@index');
    Route::post('product/add', 'ProductController@store');
    Route::get('/approved/product/{id}', 'ProductController@approved');
    Route::get('/api/publish/unpublish/product/{id}', 'ProductController@publishUnpublish');
    Route::get('/pending/product/{id}', 'ProductController@pending');
    Route::get('/deny/product/{id}', 'ProductController@deny');
    Route::post('/stock/update/product/{id}', 'ProductController@stockUpdate');
    Route::get('/edit/product/{id}', 'ProductController@edit');
    Route::post('/update/product/basicInformation/{id}', 'ProductController@updateBasicInformation');
    Route::post('/update/product/properties/{id}', 'ProductController@updateProperties');
    Route::post('/update/product/image/{id}', 'ProductController@UpdateProductImage');
    Route::get('/delete/product/image/{id}', 'ProductController@deleteImage');
    Route::get('/destroy/product/{id}', 'ProductController@destroy');
    Route::get('search/product/{search}', 'ProductController@search');
    Route::get('search/single/product/{search}', 'ProductController@searchSingleProduct');
    Route::get('/search/product/with/code/{search}', 'ProductController@searchWithCode')->name('search.product.with.code');
    Route::get('search/customer/with/phone/number/{number}', 'ProductController@searchCustomer');

    Route::get('api/get/seggested/product/for/order','ProductController@get_suggested_product');
    Route::get('api/search/seggested/product/for/order/{product_code}','ProductController@search_suggested_product');
    Route::get('api/product/stock', 'ProductController@productStockReports');
    Route::get('api/stock/report/product/all/categories/pdf', 'ProductController@stockReportAllCategory');
    Route::get('api/stock/report/product/definite/category/pdf/{category_id}', 'ProductController@stockReportCategoryWise');
    Route::get('/product/print/barcode/{id}/{how_many}', 'ProductController@printBarcode');
    Route::get('api/search/seggested/product/with/name/code/{data}','ProductController@search_suggested_product_code_name');
    Route::get('/copy/product/{id}/{copyItmes}','ProductController@CopyProducts');
    Route::post('api/product/stockin/from/anather/product', 'ProductController@stockInAnatherProduct');
    Route::get('api/product/stock/transfer', 'ProductController@stockTransfer');
    Route::get('api/ck/editor/image/upload', 'ProductController@ckEditorUpload');
    Route::get('api/create/thumbnail_img','ProductController@ThumbnailImageMakerFromExistingFile');
    //product bulk print
    Route::post('api/product/bulk/action/barcode/print', 'ProductController@filterProductForPrintBarcode');
    Route::post('api/product/bulk/barcode/print/set/in/session', 'ProductController@bulkPrintBardCodeSetSession');
    Route::get('api/product/bulk/barcode/print', 'ProductController@bulkPrintBardCode');
    Route::get('api/product/stock/tracking/report', 'ProductController@stockTracking');

    //start the coupon route
    Route::get('api/get/coupon/list', 'CouponController@index');
    Route::post('api/coupon/code/add', 'CouponController@store');
    Route::get('api/coupon/active/{id}', 'CouponController@active');
    Route::get('api/coupon/de-active/{id}', 'CouponController@deActive');
    Route::get('api/get/edit/coupon/{id}', 'CouponController@get_edit_item');
    Route::post('api/coupon/code/update/{id}', 'CouponController@update');
    Route::get('api/coupon/delete/{id}', 'CouponController@destroy');


     //start the purchase route
    Route::get('api/list/purchase', 'PurchaseController@index');
    Route::post('add/purchase', 'PurchaseController@store');
    Route::get('/active/purchase/{id}', 'PurchaseController@active');
    Route::get('/deActive/purchase/{id}', 'PurchaseController@deActive');
    Route::get('/edit/purchase/{id}', 'PurchaseController@edit');
    Route::post('/update/purchase/{id}', 'PurchaseController@update');
    Route::get('/destroy/purchase/{id}', 'PurchaseController@destroy');
    Route::get('details/purchase/{id}', 'PurchaseController@details');
    Route::get('api/purchase/search/data/{data}', 'PurchaseController@search_according_data');
    Route::get('api/purchase/date/wise/filter', 'PurchaseController@according_date_wise');
    Route::post('api/purchase/memo/upload','PurchaseController@uploadFile');



    //start the sale route
    Route::post('/sale/store', 'SaleController@store');
     Route::post('/sale/exchange/store', 'SaleController@exchangeStore');
    Route::get('/api/office/sales/list', 'SaleController@office_sale_index');
    Route::get('/api/company/sales/list', 'SaleController@company_sale_index');
    Route::get('/sale/view/{id}', 'SaleController@show');
    Route::get('/sale/paid/{id}', 'SaleController@paid');
    Route::get('/sale/returned/{id}', 'SaleController@return');
    // this route for office sale
    Route::get('api/office/sale/search/data/{data}', 'SaleController@office_sale_search_according_data');
    Route::get('api/officeSale/date/wise/filter', 'SaleController@office_sale_search_according_date_wise');
    //this route for company sale
    Route::get('api/company/sale/search/data/{data}', 'SaleController@company_sale_search_according_data');
    Route::get('api/company/sale/date/wise/filter', 'SaleController@company_sale_search_according_date_wise');





    //start the order  route
     Route::post('create/order', 'OrderController@store');
     Route::post('update/order/{id}', 'OrderController@update');

    Route::get('orders', 'OrderController@index');
    Route::get('order/view/{id}', 'OrderController@orderView');

    Route::get('approved/order/{id}', 'OrderController@approved');
    Route::get('pending/order/{id}', 'OrderController@pending');
    Route::get('delivered/order/{id}', 'OrderController@delivered');
    Route::get('shipment/order/{id}', 'OrderController@shipment');
    Route::get('return/order/{id}', 'OrderController@return');
    Route::get('cancel/order/{id}', 'OrderController@cancel');

    Route::post('order/courier/update/{id}', 'OrderController@OrderCoutierUpdate');
    Route::get('/order/search/{saerch}', 'OrderController@orderSearch');
    Route::get('/order/filter', 'OrderController@OrderFilter');
    Route::get('/order/status/wise', 'OrderController@OrderStatusWise');
    Route::get('/order/search/status/wise/{saerch}', 'OrderController@orderSearchStatusWise');
    Route::get('/order/filter/status/wise', 'OrderController@OrderFilterStatusWise');
    Route::get('api/order/comment', 'OrderController@comment');

    //order bulk action route
    Route::get('order/label/print/{id}', 'OrderController@labelPrint');
    Route::get('order/invoice/print/{id}', 'OrderController@invoicePrint');

    Route::get('pending/all/order/{id}', 'OrderController@pendingAll');
    Route::get('approved/all/order/{id}', 'OrderController@approvedAll');
    Route::post('api/shipment/all/order', 'OrderController@shipmentAll');
    Route::get('delivered/all/order/{id}', 'OrderController@deliveredAll');
    Route::get('cancel/all/order/{id}', 'OrderController@cancellAll');
    Route::get('return/all/order/{id}', 'OrderController@returnAll');

    Route::get('update/commision/reseller/order/{id}', 'OrderController@updateResellerCommison');
    Route::get('order/return/item/{id}', 'OrderController@returnItem');
    //start the others  route
    Route::get('/others', 'OthersController@others');


    //start the admin route
    Route::get('/single/admin', 'LoginController@admin');
    Route::get('/logout/admin', 'LoginController@logout');
    Route::get('/list/admin', 'AdminController@index');
    Route::post('/add/admin', 'AdminController@store');
    Route::get('/api/role/get/admin/{id}', 'AdminController@getAdminRole');
    Route::post('/api/role/update/admin/{id}', 'AdminController@updateAdminRole');
    //permissions routes
    Route::get('api/get/admin/permission/list/{id}', 'AdminController@getAdminPermission');
    Route::post('api/assign/admin/permissions/{id}', 'AdminController@assignAdminPermission');
    // admin notes
    Route::get('api/get/admin/note/list','AdminController@note_list');
    Route::get('api/delete/admin/note/{id}','AdminController@delete_note');
    Route::post('api/store/admin/new/note','AdminController@store_note');
    Route::get('api/search/admin/{data}', 'AdminController@search_admin');
    Route::get('deactive/admin/{id}', 'AdminController@deactive');
    Route::get('active/admin/{id}', 'AdminController@active');
    Route::get('/edit/admin/{id}', 'AdminController@edit');
    Route::post('/update/admin/{id}', 'AdminController@update');
    Route::post('/update/admin/password/{id}', 'AdminController@updatePassword');
    //balance routes
    Route::get('api/balance/list/all/department','AccountController@BalanceAllDepartment');
    Route::get('api/balance/list','AccountController@BalanceMohasagor');
    Route::get('api/department/wise/balance/list/{department}','AccountController@DepartmentWiseBalance');
    Route::get('api/balance/list/boost','AccountController@BoostBalance');
    Route::get('api/balance/list/mit','AccountController@MitBalance');
    Route::get('api/balance/list/tourism','AccountController@TourismBalance');
    Route::get('api/balance/list/properties','AccountController@PropertiesBalance');
    Route::get('api/balance/status/change/{id}','AccountController@BalanceStatusChange');
    Route::post('api/balance/add','AccountController@StoreBalanceAccount');

    //start the accounts route
    //start credit route
    Route::get('credits', 'AccountController@GetCredit');
    Route::post('api/credit/store', 'AccountController@StoreCredit');
    Route::get('credit/edit/{id}', 'AccountController@edit_credit');
    Route::post('credit/update/{id}', 'AccountController@update_credit');
    Route::get('credit/destroy/{id}', 'AccountController@destroy_credit');
    Route::get('api/export/credit', 'AccountController@export_credit');

    //credit due route......
    Route::get('api/credit/due', 'CreditDueController@index');
    Route::get('api/due/to/paid/{id}', 'CreditDueController@duePaid');
    Route::get('api/due/search/{search}', 'CreditDueController@search');
    Route::get('account/monlthly/report', 'AccountController@monthlyReport');

    //start debit route
    Route::get('api/debits', 'AccountController@GetDebits');
    Route::post('api/debit/store', 'AccountController@StoreDebit');
    Route::get('debit/edit/{id}', 'AccountController@edit_debit');
    Route::get('api/export/debit', 'AccountController@export_debit');
    Route::post('debit/update/{id}', 'AccountController@update_debit');
    Route::get('debit/destroy/{id}', 'AccountController@destroy_debit');
    Route::get('api/account/purpose', 'AccountController@accountPurpose');
    Route::get('api/employee/list', 'AccountController@employeeList');

    //start company route
    Route::get('api/company/with/sales/records', 'CompanyController@index');
    Route::get('company', 'CompanyController@companyList');
    Route::post('company/store', 'CompanyController@store');
    Route::get('company/edit/{id}', 'CompanyController@edit');
    Route::post('company/update/{id}', 'CompanyController@update');
    Route::get('api/profit/report', 'ReportController@profitReport');
    //start the admin reseller route
    Route::resource('admin/reseller','ResellerController');
    Route::post('/reseller/update/{id}','ResellerController@updateRseller');
    Route::get('/admin/to/reseller/{id}','ResellerController@accountAccess');
    Route::get('/api/active/reseller/{id}','ResellerController@active');
    Route::get('/api/deactive/reseller/{id}','ResellerController@deActive');
    Route::get('api/unpaid/payment/','ResellerController@unpaidPayment');
    Route::get('api/paid/payment/','ResellerController@paidPayment');
    Route::get('/api/reseller/to/paid','ResellerController@toPaid');
    Route::get('api/payment/invoice','ResellerController@paymentInvoice');
    Route::get('api/details/payment/invoice/{id}','ResellerController@paymentInvoiceDetails');




  // Route::resource('/reseller','ResellerController');

  Route::get('api/reseller/list','ResellerController@getReseller');
  Route::get('api/reseller/remove/{id}','ResellerController@destroy');
  Route::post('api/reseller/add','ResellerController@addReseller');
  Route::post('api/reseller/update/{id}','ResellerController@updateRseller');
  Route::get('api/admin/to/reseller/{id}','ResellerController@accountAccess');
  Route::get('api/search/reseller/{data}','ResellerController@reseller_search');
  Route::get('api/export/resellers','ResellerController@export_reseller');


  //start role and permission route
  Route::post('api/role/store','RoleController@store');
  Route::get('api/roles','RoleController@index');
  Route::get('api/permissions/edit/role/{id}','RoleController@editRolePermissons');
  Route::post('api/permissions/update/role/{id}','RoleController@updateRolePermissons');

  Route::post('api/fund/transfer/store','FundTransferController@BalaceTransferStore');
  Route::get('api/fund/transfer','FundTransferController@BalanceTransfer');
  Route::post('api/department/loan/transfer','FundTransferController@depatmentLoanTransfer');
  Route::get('api/department/loan/transaction/history/{id}','FundTransferController@depatmentLoanTransactionDetails');
  Route::get('api/department/loan','FundTransferController@depatmentLoanList');

  //order export
  Route::get('/order/export/{status}/{courier_id}', 'OrderController@exportOrder');
  Route::get('/export/orders/for/paper-fly/{id}', 'OrderController@exportOrderForPaperFly');
  Route::get('/export/orders/for/redx/{id}', 'OrderController@exportOrderForRedx');


    // general setting route is start from here
    Route::get('api/get/site/info','GeneralSettingController@get_site_info');
    Route::post('api/edit/site/info/{id}','GeneralSettingController@edit_site_info');

    Route::get('api/get/occasion/campaign','SaleCampaignController@get_occasional_campaign');
    Route::post('api/edit/occasion/campaign/{id}','SaleCampaignController@edit_occasional_campaign');
    Route::get('api/get/flash/deals','SaleCampaignController@get_flash_deals_campaign');
    Route::post('api/edit/flash/deals/{id}','SaleCampaignController@edit_flash_deals_campaign');
    Route::get('api/get/seasional/campaign','SaleCampaignController@get_seasional_campaign');
    Route::post('api/edit/seasional/campaign/{id}','SaleCampaignController@edit_seasional_campaign');
    Route::get('api/get/buy/one/get/one/offer','SaleCampaignController@get_buy_one_get_one_campaign');
    Route::post('api/edit/buy/one/get/one/offer/{id}','SaleCampaignController@edit_buy_one_get_one_campaign');

    //parallax banner
    Route::get('api/get/parallax/banner','SaleCampaignController@parallaxBanner');
    Route::post('api/edit/parallax/banner/{id}','SaleCampaignController@parallaxBannerUpdate');
   //start the loan route
    Route::get('api/loan','LoanController@index');
    Route::post('api/loaner/store','LoanController@storeLoaner');
    Route::get('api/loaners','LoanController@loaners');
    Route::get('api/loaners/details/{id}','LoanController@loanersdetails');
    Route::post('api/loan/store','LoanController@storeLoan');
    Route::get('api/download/all/loan/pdf','LoanController@download_all_record');
    Route::get('api/loan/history/download/pdf/{id}','LoanController@download_loan_history');
    Route::get('api/loand/paid/history/download/pdf/{id}','LoanController@download_loan_paid_history');
    //company assets route is here
    Route::get('api/company/assets','AssetsController@get_assets');
    Route::post('api/company/assets/add','AssetsController@store_assets');
    Route::get('api/company/assets/edit/{id}','AssetsController@get_asset_item');
    Route::post('api/company/assets/update/{id}','AssetsController@update_asset_item');
    Route::get('api/company/assets/delete/{id}','AssetsController@delete_asset_item');
    Route::get('api/download/assets/pdf','AssetsController@download_assets');
    // Route::get('api/get/customer/review/on/products',)
    Route::get('api/get/customer/review/on/products','ProductReviewController@get_customer_review');
    Route::get('api/deActive/review/of/customer/{id}','ProductReviewController@deactive_customer_review');
    Route::get('api/active/review/of/customer/{id}','ProductReviewController@active_customer_review');
    Route::get('api/remove/review/of/customer/{id}','ProductReviewController@remove_customer_review');

    //company investment route is here
    Route::get('api/company/investor','InvestmentController@get_investors');
    Route::post('api/company/investor/add','InvestmentController@store');
    Route::get('api/company/investor/get/{id}','InvestmentController@getInvestor');
    Route::post('api/company/investor/update/{id}','InvestmentController@updateInvestor');
    Route::get('api/company/investor/details/{id}','InvestmentController@get_investor_details');
    Route::get('api/add/more/invest/{id}','InvestmentController@add_more_invest');
    Route::get('api/company/investor/list','InvestmentController@investor_list');
    Route::get('api/download/all/investment/pdf','InvestmentController@download_investors');
    Route::get('api/investor/paid/history/download/pdf/{id}','InvestmentController@download_profit_paid');
    Route::get('api/invest/history/download/pdf/{id}','InvestmentController@download_investor_record');



     //company investment route is here
     Route::get('api/print/houses','PrintHouseController@index');
     Route::post('api/print/house/add','PrintHouseController@store');
     Route::get('api/print/house/details/{id}','PrintHouseController@get_print_house_details');
     Route::post('api/add/product/for/print','PrintHouseController@add_product_for_print');
     Route::get('api/print/house/list','PrintHouseController@print_house_list');
     Route::get('api/get/print/details','PrintHouseController@print_details');
     Route::get('api/get/receive/product/details','PrintHouseController@recieve_print_details');
     Route::get('api/get/printed/products/{id}','PrintHouseController@printed_product_list');
     Route::post('api/receive/printed/product/{id}','PrintHouseController@receive_printed_products');

     Route::get('api/get/prop/up/banner','SaleCampaignController@get_prop_up');
     Route::post('api/edit/prop/up/banner/{id}','SaleCampaignController@update_prop_up_banner');

    //company bill statements route is here
    Route::get('api/bill/statement/list','BillStatementController@bill_list');
    Route::post('api/bll/statement/add','BillStatementController@store');
    Route::get('api/bll/per/month/add','BillStatementController@store_bill_per_month');
    Route::get('api/bll/statement/details/{id}','BillStatementController@bill_statement_details');
    Route::get('api/change/bill/statement/status/{id}','BillStatementController@changeStatus');

    //supplier note route is here
    Route::get('api/get/note/list/{id}','SupplierController@note_list');
    Route::get('api/delete/note/{id}','SupplierController@delete_note');
    Route::post('api/store/new/note/{id}','SupplierController@store_note');
    Route::post('api/supplier/reverse/payment/store', 'SupplierController@receiveReversePayment');
    Route::get('company/active/status/{id}', 'CompanyController@active');
    Route::get('company/de-active/status/{id}', 'CompanyController@deActive');
    Route::post('api/get/company/sale/payment', 'SaleController@companySalePayment');
    Route::get('api/company/sale/payment/details/{id}', 'SaleController@companyPayment');
    Route::get('api/company/sale/details/{id}', 'SaleController@CompanySaleDetails');
    Route::get('api/company/sale/due/list', 'SaleController@CompanySaleDuelist');
    //supplier sales
    Route::get('api/supplier/reverse/sales/records/{id}', 'SaleController@SupplierSaleDetails');


    Route::post('api/campaign/store','CampaignController@store');
    Route::get('api/campaign','CampaignController@index');
    Route::get('api/campaign/edit/{id}','CampaignController@edit');
    Route::post('api/campaign/update/{id}','CampaignController@update');
    Route::get('api/campaign/remove/{id}','CampaignController@destroy');

    Route::get('api/campaign/product/remove/{productId}','CampaignController@removeProductfromProduct');
    Route::get('api/product/assign/to/campaign/{product_code}','CampaignController@assignProduct');
    Route::get('api/campaign/product/{campaign_id}','CampaignController@campaignProducts');
    Route::get('api/deActive/campaign/{campaign_id}','CampaignController@deactive');
    Route::get('api/active/campaign/{campaign_id}','CampaignController@active');



    Route::post('api/campaign/store','CampaignController@store');
    Route::get('api/campaign','CampaignController@index');
    Route::get('api/campaign/edit/{id}','CampaignController@edit');
    Route::post('api/campaign/update/{id}','CampaignController@update');
    Route::get('api/campaign/remove/{id}','CampaignController@destroy');

  Route::get('api/campaign/product/remove/{productId}','CampaignController@removeProductfromProduct');
  Route::get('api/campaign/product/remove/{productId}','CampaignController@removeProductfromProduct');
  Route::get('api/product/assign/to/campaign/{product_code}','CampaignController@assignProduct');
  Route::get('api/campaign/product/{campaign_id}','CampaignController@campaignProducts');
  Route::get('api/deActive/campaign/{campaign_id}','CampaignController@deactive');
  Route::get('api/active/campaign/{campaign_id}','CampaignController@active');

   ///start the campaign image route

  Route::get('api/campaign/image/{campaign_id}','CampaignSliderController@index');
  Route::post('api/campaign/image','CampaignSliderController@store');
  Route::get('api/campagin/image/remove/{image_id}','CampaignSliderController@destroy');

   //office sale invoice
    Route::get('api/print/sale/invoice/{id}','OrderController@officeSaleInvoicePrint') ;
    Route::get('api/get/order/statistic', 'OrderController@orderStatistic');
    Route::get('api/get/due/customer/payment/history/{phone}', 'CreditDueController@paymentHistory');





    //-----------director routes start -------------------
    Route::get('api/directors','DirectorController@getDirectors');
    Route::post('api/director/add','DirectorController@addDirector');
    Route::post('api/director/update/info/{id}','DirectorController@updateDirector');
    Route::get('api/get/director/{id}','DirectorController@getDirector');
    Route::get('api/director/search/{phone}','DirectorController@searchDirector');
    Route::post('api/store/director/payment','DirectorController@storeDirectorPayment');
    Route::post('api/refund/director/payment','DirectorController@refundDirectorPayment');
   //-----------director routes end -------------------






});

Route::post('/admin/login', 'Admin\LoginController@login');
Route::post('api/verify/otp/admin/login', 'Admin\LoginController@otpVerification');
Route::get('check/session/admin', 'Admin\LoginController@sessionCheck');
Route::get('/resller', 'Reseller\HomeController@home');


//opt verify
 Route::post('send/otp', 'Frontend\HomeController@SendOtp');
 Route::get('/verify/otp/code', 'Frontend\HomeController@verifyCodeOtp');

 Route::get('api/get/city/for/order/checkout','Admin\SubCityController@cityList');
 Route::get('city/wise/sub/city/{id}','Admin\SubCityController@cityWiseSubCity');

 Route::get('login/google', 'Frontend\Social\GoogleLoginController@redirectToProvider');
 Route::get('callback/google', 'Frontend\Social\GoogleLoginController@handleProviderCallback');




//start the ssl route
Route::group([

   // 'middleware' => 'auth',

], function () {

    Route::post('/pay', 'SslCommerzPaymentController@index');
    Route::post('/pay-via-ajax','SslCommerzPaymentController@payViaAjax');
    Route::post('/success',  'SslCommerzPaymentController@success');
    Route::post('/fail', 'SslCommerzPaymentController@fail');
    Route::post('/cancel', 'SslCommerzPaymentController@cancel');
    Route::post('/ipn','SslCommerzPaymentController@ipn');
    Route::get('api/get/order/{id}', 'SslCommerzPaymentController@getOrder');


});



Route::get('/sociallogin/google','SocialiteController@login')->name('login.google');
Route::get('callback/google','SocialiteController@redirect');


Route::get('login/facebook','SocialiteController@loginFacebook');
Route::get('facebook/callback','SocialiteController@redirectFacebook');




// laravel and vue mix routing
// when any one type backend / anything as url
// then laravel router transfer  to vue-routing

Route::get('/backend/{any}', function () {
     return view('admin.master');
})->where('any', '^(?!api\/)[\/\w\.\,-]*');


Route::get('/{any}', function () {
 return view('frontend.master');

})->where('any', '^(?!api\/)[\/\w\.\,-]*');


