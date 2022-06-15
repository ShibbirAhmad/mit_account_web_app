<?php

use Illuminate\Support\Facades\Route;

Route::group([
    // 'middleware' => 'admin',
    'namespace' => 'Frontend',
    'prefix' => '_public/'
], function () {

    //occasion buy one get one and seasoanl campaign
    Route::get('api/publish/seasional/campaign','HomeController@publish_seasional_campaign');
    Route::get('api/publish/occasional/campaign','HomeController@publish_occation_campaign');
    Route::get('api/publish/buy/one/get/one/campaign','HomeController@publish_buy_one_get_one_campaign');

    //flash sale
    Route::get('api/flash/deals/campagin', 'HomeController@flashDeals');
    // public carrier route
    Route::get('carrier/list','CarrierController@index');
    Route::get('carrier/details/{id}','CarrierController@carrier_details');
    Route::post('api/carrier/apply/by/job/{id}','CarrierController@job_apply');
    //route for get team
    Route::get('team/members','HomeController@get_team_members');

    //public product route
    Route::get('products', 'HomeController@products');
    Route::get('product/{slug}', 'HomeController@product');
    Route::get('product/id/wise/{id}', 'HomeController@productIdWise');
    Route::get('related/products', 'HomeController@relatedProduct');
    //route for subscriber and contact
    Route::post('add/subscriber','SubscriberController@addSubscriber');
    Route::post('customer/contact','ContactController@contactCustomer');

    //public category route
    Route::get('category', 'HomeController@category');
    Route::get('category/wise/product', 'HomeController@categoryWiseProduct');
    Route::get('category/wise/product/price/filter', 'HomeController@categoryWiseProductPriceFilter');
    Route::get('merchant/wise/product', 'HomeController@merchantWiseProduct');

    Route::get('api/sort/product/according/to/asc/desc', 'HomeController@sort_by_price');
    Route::get('api/sort/product/sub/category/according/to/asc/desc', 'HomeController@sub_category_sort_by_price');
    Route::get('api/sort/product/sub/sub/category/according/to/asc/desc', 'HomeController@sub_sub_category_sort_by_price');
    Route::get('api/get/single/prodocut/for/quick/view/{id}','HomeController@get_quick_view_product');

    //public sub category route
    Route::get('sub/category/{slug}', 'HomeController@subCategory');
    Route::get('sub/category/wise/product', 'HomeController@subCategoryWiseProduct');
    Route::get('sub/sub/category/product', 'HomeController@subSubSubCategoryProduct');

    //public sub-sub category route
    Route::get('sub/sub/category/{slug}', 'HomeController@subSubCategory');
    Route::get('sub/sub/category/wise/product', 'HomeController@subSubCategoryWiseProduct');

    //slider display route started here
    Route::get('slider', 'HomeController@slider');
    Route::get('api/display/category/slider', 'HomeController@display_category_slider');
    Route::get('api/display/sub/category/slider', 'HomeController@display_sub_category_slider');
    Route::get('api/display/sub/sub/category/slider', 'HomeController@display_sub_sub_category_slider');
    //slider route end

    Route::get('offers', 'HomeController@offers');
    Route::get('product', 'HomeController@product');
    Route::get('search/products/{search}', 'HomeController@SearchProduct');
    Route::get('search/products/{search}', 'HomeController@SearchProduct');
    Route::get('product/images/{slug}', 'HomeController@productImage');
    //flash sale
    Route::get('flash/sale', 'HomeController@flashSale');
    //cart route..........
    Route::get('addToCart', 'CartController@addCart');
    Route::get('cartToContent', 'CartController@carToContent');
    Route::get('cartToUpdate', 'CartController@carToUpdate');
    Route::get('cartToDestroy', 'CartController@carToDestroy');
    //user authentication route
    Route::post('userToRegister','AuthController@register');
    Route::post('userToLogin','AuthController@login');
    Route::get('userToCheck','AuthController@chekAuthUser');
    Route::post('api/user/password/reset/send/code','AuthController@resetCode');
    Route::post('api/user/password/verify/code/{mobile_no}','AuthController@codeVerify');
    Route::post('api/user/password/reset/{mobile_no}','AuthController@ResetPassword');
    Route::post('send/code/forgotten/user','AuthController@send_password_reset_code')->middleware(['guest']);
    Route::get('api/get/all/falsh/deals', 'HomeController@get_all_flash_deals');
    Route::get('campaigns', 'HomeController@campaigns');
    Route::get('campaign/{campaign_id}', 'HomeController@campaign');
    Route::get('cart/check', 'HomeController@cartCheck');
    Route::post('checkout', 'OrderController@checkout');
    Route::get('apply/coupon/code', 'HomeController@ApplyCoupon');
});



//:::::authenticate user router::::::

Route::group([
     'middleware' => 'auth',
    'namespace' => 'Frontend',
    'prefix' => '_public/'
], function () {

    //checkout route
    //customer order list route
    Route::get('customer/order/list', 'OrderController@orderList');
    Route::get('customer/order/last', 'OrderController@userLastOrder');
    Route::get('api/user/order/details/{id}', 'OrderController@user_order_details');
    Route::get('customer/order/invoice/print/{id}', 'OrderController@customer_invoice_print');
    //user profile update
    Route::post('update/user/profile', 'AuthController@userProfileUpdate');
    Route::post('user/password/update', 'AuthController@updatePassword');
    //logout route
    Route::get('user/logout', 'AuthController@logout');
    Route::post('user/set/new/password', 'AuthController@user_set_new_password');
    //api add  product review
    Route::post('api/add/customer/review/to/product/{id}', 'HomeController@customer_review_to_product');


});
