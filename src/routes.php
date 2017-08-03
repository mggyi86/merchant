<?php

Route::group(['namespace' => 'Hsubuu\Merchant\Controllers', 'prefix' => 'hsubuu/merchant'], function () {
	Route::post('/login', ['as' => 'merchant_patch', 'uses' => 'MerchantController@login']);
	Route::get('/', 'MerchantController@merchant');
	Route::post('/change_password', 'MerchantController@change_password');
	Route::post('/give_code', 'MerchantController@give_code');
	Route::get('/used_code', 'MerchantController@used_code');
	Route::get('/exchange_gift', 'MerchantController@exchange_gift');
});