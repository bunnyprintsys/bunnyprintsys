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
    Route::get('/{type}', 'OrderController@index')->name('order.index');
});

Route::group(['prefix' => 'transaction'], function() {
    Route::get('/', 'TransactionController@index')->name('transaction.index');
    Route::get('/data', 'TransactionController@getDataSettingIndex')->name('transaction.data');
    Route::get('/invoice/{transactionId}', 'TransactionController@getInvoice');
});
Route::group(['prefix' => 'price'], function() {
    Route::get('/{type}', 'PriceController@index')->name('price.index');
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

    Route::group(['prefix' => 'bank'], function() {
        Route::get('/all', 'BankController@getAllApi');
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
        Route::post('/create/product/{product_id}', 'DeliveryController@createProductDeliveryByProductIdApi');

        Route::post('/product-delivery/all', 'ProductDeliveryController@getAllApi');
        Route::post('/product-delivery/create', 'ProductDeliveryController@createApi');
        Route::post('/product-delivery/edit', 'ProductDeliveryController@editApi');
    });
    Route::group(['prefix' => 'delivery-method'], function() {
        Route::get('/all', 'DeliveryMethodController@getAllDeliveryMethodsApi');
        Route::post('/', 'DeliveryMethodController@storeDeliveryMethodApi');
        Route::delete('/{id}', 'DeliveryMethodController@deleteSingleDeliveryMethod');
    });
    Route::group(['prefix' => 'finishings'], function() {
        Route::post('/all', 'FinishingController@getAllApi');
        Route::post('/update/{id}', 'FinishingController@updateApi');
        Route::post('/', 'FinishingController@createApi');
        Route::delete('/{id}', 'FinishingController@deleteApi');

        Route::post('/create/product/{product_id}', 'FinishingController@createProductFinishingByProductIdApi');
        Route::post('/delete/product/{product_id}', 'FinishingController@deleteProductFinishingByProductIdApi');
        Route::post('/binded/product/{productId}', 'FinishingController@getBindedFinishingByProductId');
        Route::post('/exclude-binded/product/{productId}', 'FinishingController@getNonBindedFinishingByProductId');
        Route::post('/product-finishing/all', 'ProductFinishingController@getAllApi');
    });
    Route::group(['prefix' => 'frames'], function() {
        Route::post('/all', 'FrameController@getAllApi');
        Route::post('/update/{id}', 'FrameController@updateApi');
        Route::post('/', 'FrameController@createApi');
        Route::delete('/{id}', 'FrameController@deleteApi');

        Route::post('/create/product/{product_id}', 'FrameController@createProductFrameByProductIdApi');
        Route::post('/delete/product/{product_id}', 'FrameController@deleteProductFrameByProductIdApi');
        Route::post('/binded/product/{productId}', 'FrameController@getBindedFrameByProductId');
        Route::post('/exclude-binded/product/{productId}', 'FrameController@getNonBindedFrameByProductId');
        Route::post('/product-frame/all', 'ProductFrameController@getAllApi');

    });
    Route::group(['prefix' => 'laminations'], function() {
        Route::post('/all', 'LaminationController@getAllApi');
        Route::get('/product/{product_id}', 'LaminationController@getAllLaminationsByProductIdApi');
        Route::post('/{id}', 'LaminationController@updateProductLaminationByIdApi');

        Route::post('/update/{id}', 'LaminationController@updateApi');
        Route::post('/', 'LaminationController@createApi');
        Route::delete('/{id}', 'LaminationController@deleteApi');

        Route::post('/create/product/{product_id}', 'LaminationController@createProductLaminationByProductIdApi');
        Route::post('/delete/product/{product_id}', 'LaminationController@deleteProductLaminationByProductIdApi');
        Route::post('/binded/product/{productId}', 'LaminationController@getBindedLaminationByProductId');
        Route::post('/exclude-binded/product/{productId}', 'LaminationController@getNonBindedLaminationByProductId');

        Route::post('/product-lamination/all', 'ProductLaminationController@getAllApi');
        Route::post('/product-lamination/create', 'ProductLaminationController@createApi');
        Route::post('/product-lamination/edit', 'ProductLaminationController@editApi');
    });
    Route::group(['prefix' => 'materials'], function() {
        Route::post('/all', 'MaterialController@getAllApi');
        Route::post('/update/{id}', 'MaterialController@updateApi');
        Route::post('/', 'MaterialController@createApi');
        Route::delete('/{id}', 'MaterialController@deleteApi');

        Route::get('/product/{id}', 'MaterialController@getAllMaterialsByProductIdApi');
        Route::post('/{id}', 'MaterialController@updateProductMaterialByIdApi');
        Route::post('/create/product/{product_id}', 'MaterialController@createProductMaterialByProductIdApi');
        Route::post('/delete/product/{product_id}', 'MaterialController@deleteProductMaterialByProductIdApi');
        Route::post('/binded/product/{productId}', 'MaterialController@getBindedMaterialByProductId');
        Route::post('/exclude-binded/product/{productId}', 'MaterialController@getNonBindedMaterialByProductId');

        Route::post('/product-material/all', 'ProductMaterialController@getAllApi');
        Route::post('/product-material/create', 'ProductMaterialController@createApi');
        Route::post('/product-material/edit', 'ProductMaterialController@editApi');
        Route::post('/product-material/excluded/{productId}', 'ProductMaterialController@getExcludedMaterialByProductId');
    });
    Route::group(['prefix' => 'member'], function() {
        Route::get('/', 'MemberController@getMembersApi');
        Route::post('/store-update', 'MemberController@storeUpdateMemberApi');
    });
    Route::group(['prefix' => 'order'], function() {
        Route::post('/label-sticker/quotation', 'OrderController@getLabelstickerQuotationApi');
    });
    Route::group(['prefix' => 'orderquantities'], function() {
        Route::post('/all', 'OrderQuantityController@getAllApi');
        Route::post('/create', 'OrderQuantityController@createApi');
        Route::post('/edit', 'OrderQuantityController@editApi');

        Route::post('/{id}', 'OrderQuantityController@updateOrderquantityByIdApi');
        Route::post('/create/product/{product_id}', 'OrderQuantityController@createOrderquantityByProductIdApi');
    });
    Route::group(['prefix' => 'payment-term'], function() {
        Route::get('/all', 'PaymentTermController@getAllApi');
    });
    Route::group(['prefix' => 'product'], function() {
        Route::get('/all', 'ProductController@getProductsApi');
        Route::post('/update/{id}', 'ProductController@updateApi');
        Route::post('/', 'ProductController@createApi');
        Route::delete('/{id}', 'ProductController@deleteApi');
    });
    Route::group(['prefix' => 'profile'], function() {
        Route::get('/', 'ProfileController@getProfilesApi');
        Route::post('/store-update', 'ProfileController@storeUpdateProfileApi');
    });
    Route::group(['prefix' => 'quantitymultipliers'], function() {
        Route::post('/all', 'QuantityMultiplierController@getAllApi');
        Route::post('/create', 'QuantityMultiplierController@createApi');
        Route::post('/edit', 'QuantityMultiplierController@editApi');

        Route::get('/product/{id}', 'QuantityMultiplierController@getAllQuantitymultipliersByProductIdApi');
        Route::post('/{id}', 'QuantityMultiplierController@updateQuantitymultiplierByIdApi');
        Route::post('/create/product/{product_id}', 'QuantityMultiplierController@createQuantitymultiplierByProductIdApi');
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
        Route::post('/all', 'ShapeController@getAllShapesApi');
        Route::post('/update/{id}', 'ShapeController@updateApi');
        Route::post('/', 'ShapeController@createApi');
        Route::delete('/{id}', 'ShapeController@deleteApi');

        Route::post('/product-shape/all', 'ProductShapeController@getAllApi');
        Route::post('/product-shape/create', 'ProductShapeController@createApi');
        Route::post('/product-shape/edit', 'ProductShapeController@editApi');
        Route::post('/create/product/{product_id}', 'ShapeController@createProductShapeByProductIdApi');
        Route::post('/delete/product/{product_id}', 'ShapeController@deleteProductShapeByProductIdApi');
        Route::post('/binded/product/{productId}', 'ShapeController@getBindedShapeByProductId');
        Route::post('/exclude-binded/product/{productId}', 'ShapeController@getNonBindedShapeByProductId');
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