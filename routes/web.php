<?php

auth()->loginUsingId(1, true);
Auth::routes();

Route::get('/', 'HomeController@index')->name('home.index');

Route::get('/home', 'HomeController@index')->name('home.index');

Route::group(['prefix' => 'customer'], function() {
    Route::get('/', 'CustomerController@index')->name('customer.index');
});
Route::group(['prefix' => 'member'], function() {
    Route::get('/', 'MemberController@index')->name('member.index');
});
Route::group(['prefix' => 'admin'], function() {
    Route::get('/', 'AdminController@index')->name('admin.index');
});
Route::group(['prefix' => 'order'], function() {
    Route::get('/', 'OrderController@index')->name('order.index');
});
Route::group(['prefix' => 'transaction'], function() {
    Route::get('/', 'TransactionController@index')->name('transaction.index');
    Route::get('/data', 'TransactionController@getDataSettingIndex')->name('transaction.data');
    Route::get('/invoice/{transactionId}', 'TransactionController@getInvoice');
});
Route::group(['prefix' => 'price'], function() {
    Route::get('/', 'PriceController@index')->name('price.index');
});
Route::group(['prefix' => 'product'], function() {
    Route::get('/', 'ProductController@index')->name('product.index');
});
Route::group(['prefix' => 'profile'], function() {
    Route::get('/', 'ProfileController@index')->name('profile.index');
});
Route::group(['prefix' => 'voucher'], function() {
    Route::get('/', 'VoucherController@index')->name('voucher.index');
});
Route::group(['prefix' => 'public'], function(){
    Route::get('/label-sticker', 'HomeController@getLabelStickerQuotationIndex')->name('public.quotation.label-sticker');
    Route::get('/api/countries', 'HomeController@getCountriesApi');
});

Route::group(['prefix' => 'api'], function() {

    Route::group(['prefix' => 'admin'], function() {
        Route::get('/', 'AdminController@getAdminsApi');
        Route::post('/store-update', 'AdminController@storeUpdateAdminApi');
    });
    Route::group(['prefix' => 'country'], function() {
        Route::get('/all', 'CountryController@getAllCountriesApi');
    });
    Route::group(['prefix' => 'customer'], function() {
        Route::get('/', 'CustomerController@getCustomersApi');
        Route::post('/store-update', 'CustomerController@storeUpdateCustomerApi');
        Route::post('/address', 'CustomerController@getAddressApi');
    });
    Route::group(['prefix' => 'deliveries'], function() {
        Route::get('/all', 'DeliveryController@getAllDeliveriesApi');
        Route::get('/product/{product_id}', 'DeliveryController@getAllDeliveriesByProductIdApi');
        Route::post('/{id}', 'DeliveryController@updateProductDeliveryByIdApi');
    });
    Route::group(['prefix' => 'delivery-method'], function() {
        Route::get('/all', 'DeliveryMethodController@getAllDeliveryMethodsApi');
        Route::post('/', 'DeliveryMethodController@storeDeliveryMethodApi');
        Route::delete('/{id}', 'DeliveryMethodController@deleteSingleDeliveryMethod');
    });
    Route::group(['prefix' => 'laminations'], function() {
        Route::get('/all', 'LaminationController@getAllLaminationsApi');
        Route::get('/product/{product_id}', 'LaminationController@getAllLaminationsByProductIdApi');
        Route::post('/{id}', 'LaminationController@updateProductLaminationByIdApi');
    });
    Route::group(['prefix' => 'materials'], function() {
        Route::get('/all', 'MaterialController@getAllMaterialsApi');
        Route::get('/product/{id}', 'MaterialController@getAllMaterialsByProductIdApi');
        Route::post('/{id}', 'MaterialController@updateProductMaterialByIdApi');
    });
    Route::group(['prefix' => 'member'], function() {
        Route::get('/', 'MemberController@getMembersApi');
        Route::post('/store-update', 'MemberController@storeUpdateMemberApi');
    });
    Route::group(['prefix' => 'order'], function() {
        Route::post('/label-sticker/quotation', 'OrderController@getLabelstickerQuotationApi');
    });
    Route::group(['prefix' => 'orderquantities'], function() {
        Route::get('/all', 'OrderQuantityController@getAllOrderquantitiesApi');
        Route::post('/{id}', 'OrderQuantityController@updateOrderquantityByIdApi');
    });
    Route::group(['prefix' => 'product'], function() {
        Route::get('/all', 'ProductController@getProductsApi');
    });
    Route::group(['prefix' => 'profile'], function() {
        Route::get('/', 'ProfileController@getProfilesApi');
        Route::post('/store-update', 'ProfileController@storeUpdateProfileApi');
    });
    Route::group(['prefix' => 'quantitymultipliers'], function() {
        Route::get('/product/{id}', 'QuantityMultiplierController@getAllQuantitymultipliersByProductIdApi');
        Route::post('/{id}', 'QuantityMultiplierController@updateQuantitymultiplierByIdApi');
    });
    Route::group(['prefix' => 'registration'], function() {
        Route::post('/', 'Auth\RegisterController@register');
        Route::post('/validate/phonenumber', 'Auth\RegisterController@validatePhoneNumber');
        Route::post('/validate/user-phonenumber', 'Auth\RegisterController@validateUserPhoneNumber');
        Route::post('/validate/info', 'Auth\RegisterController@validateApplicantInfo');
        Route::post('/validate/password', 'Auth\RegisterController@validateApplicantPassword');
        Route::post('/password/update', 'Auth\RegisterController@updateUserPassword');
        Route::post('/create-otp', 'Auth\RegisterController@createOtp');
        Route::post('/validate-otp', 'Auth\RegisterController@validateOtp');
    });
    Route::group(['prefix' => 'sales-channel'], function() {
        Route::get('/all', 'SalesChannelController@getAllSalesChannelsApi');
        Route::post('/', 'SalesChannelController@storeSalesChannelsApi');
        Route::delete('/{id}', 'SalesChannelController@deleteSingleSalesChannel');
    });
    Route::group(['prefix' => 'shapes'], function() {
        // Route::get('/all', 'ShapeController@getAllShapesApi');
        Route::get('/all', 'ShapeController@getAllShapesApi');
        Route::get('/product/{id}', 'ShapeController@getAllShapesByProductIdApi');
        Route::post('/{id}', 'ShapeController@updateProductShapeByIdApi');
        Route::post('/', 'ShapeController@storeProductShapeApi');
        Route::delete('/{id}', 'ShapeController@deleteSingleProductShape');
    });
    Route::group(['prefix' => 'state'], function() {
        Route::get('/all', 'StateController@getAllStatesApi');
        Route::get('/country/{country_id}', 'StateController@getStatesByCountryId');
    });
    Route::group(['prefix' => 'status'], function() {
        Route::get('/all', 'StatusController@getAllStatusesApi');
        Route::post('/', 'StatusController@storeStatusApi');
        Route::delete('/{id}', 'StatusController@deleteSingleStatus');
    });
    Route::group(['prefix' => 'transaction'], function() {
        Route::get('/', 'TransactionController@getTransactionsApi');
        Route::post('/store-update', 'TransactionController@storeUpdateTransactionApi');
        Route::post('/create', 'TransactionController@createTransactionApi');
        Route::post('/update/{id}', 'TransactionController@updateTransactionApi');
    });
    Route::group(['prefix' => 'user'], function() {
        Route::post('/{id}/status-toggle', 'UserController@toggleUserStatusApi');
        Route::get('/account', 'UserController@userAccountIndex')->name('user.account.index');
        Route::get('/self', 'UserController@getLoginUser');
        Route::post('/store-update', 'UserController@storeUpdateUserApi');
    });
    Route::group(['prefix' => 'voucher'], function() {
        Route::get('/', 'VoucherController@getVouchersApi');
        Route::post('/store-update', 'VoucherController@storeUpdateVoucherApi');
    });
});