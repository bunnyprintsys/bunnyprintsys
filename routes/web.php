<?php

auth()->loginUsingId(1, true);
Route::get('/', 'HomeController@index')->name('home.index');

Route::group(['prefix' => 'api'],function() {
    Route::group(['prefix' => 'registration'], function() {
        Route::post('/', 'Auth\RegisterController@register');
    });
});

Auth::routes();

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
});

Route::group(['prefix' => 'price'], function() {
    Route::get('/', 'PriceController@index')->name('price.index');
});

Route::group(['prefix' => 'profile'], function() {
    Route::get('/', 'ProfileController@index')->name('profile.index');
});

Route::group(['prefix' => 'voucher'], function() {
    Route::get('/', 'VoucherController@index')->name('voucher.index');
});

Route::group(['prefix' => 'api'], function() {

    Route::group(['prefix' => 'admin'], function() {
        Route::get('/', 'AdminController@getAdminsApi');
        Route::post('/store-update', 'AdminController@storeUpdateAdminApi');
    });

    Route::group(['prefix' => 'customer'], function() {
        Route::get('/', 'CustomerController@getCustomersApi');
        Route::post('/store-update', 'CustomerController@storeUpdateCustomerApi');
    });

    Route::group(['prefix' => 'deliveries'], function() {
        Route::get('/all', 'DeliveryController@getAllDeliveriesApi');
        Route::get('/product/{product_id}', 'DeliveryController@getAllDeliveriesByProductIdApi');
        Route::post('/{id}', 'DeliveryController@updateProductDeliveryByIdApi');
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

    Route::group(['prefix' => 'profile'], function() {
        Route::get('/', 'ProfileController@getProfilesApi');
        Route::post('/store-update', 'ProfileController@storeUpdateProfileApi');
    });

    Route::group(['prefix' => 'quantitymultipliers'], function() {
        Route::get('/product/{id}', 'QuantityMultiplierController@getAllQuantitymultipliersByProductIdApi');
        Route::post('/{id}', 'QuantityMultiplierController@updateQuantitymultiplierByIdApi');
    });

    Route::group(['prefix' => 'shapes'], function() {
        Route::get('/all', 'ShapeController@getAllShapesApi');
        Route::get('/product/{product_id}', 'ShapeController@getAllShapesByProductIdApi');
        Route::post('/{id}', 'ShapeController@updateProductShapeByIdApi');
    });

    Route::group(['prefix' => 'transaction'], function() {
        Route::get('/', 'TransactionController@getTransactionsApi');
        Route::post('/store-update', 'TransactionController@storeUpdateTransactionApi');
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