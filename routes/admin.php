<?php

use Illuminate\Support\Facades\Route;



Route::group(['prefix'=>'admin'],function (){
    Route::get('login', 'AuthController@index')->name('admin.login');
    Route::POST('login', 'AuthController@login')->name('admin.login');
});

Route::group(['prefix'=>'admin','middleware'=>'auth:admin'],function (){
    Route::get('/', function () {
        return view('Admin/index');
    })->name('adminHome');


    #### Admins ####
    Route::resource('admins','AdminController');
    Route::POST('delete_admin','AdminController@delete')->name('delete_admin');
    Route::get('my_profile','AdminController@myProfile')->name('myProfile');

    #### Contact ###
    Route::group(['prefix' => 'contacts'], function () {
        Route::get('/', 'ContactUsController@index')->name('contact.index');
        Route::post('delete', 'ContactUsController@delete')->name('delete_contact');
    });

    #### suggestions ###
    Route::group(['prefix' => 'suggestions'], function () {
        Route::get('/', 'SuggestionController@index')->name('suggestion.index');
        Route::post('delete', 'SuggestionController@delete')->name('delete_suggestion');
    });

    #### Sliders ####
    Route::resource('sliders','SlidersController');
    Route::POST('slider.delete','SlidersController@delete')->name('slider.delete');

    #### Categories ####
    Route::resource('categories','CategoryController');
    Route::POST('category.delete','CategoryController@delete')->name('category.delete');

    #### SubCategories ####
    Route::resource('subcategories','SubCategoryController');
    Route::POST('subcategory.delete','SubCategoryController@delete')->name('subcategory.delete');

    #### Products ####
    Route::resource('products','ProductController');
    Route::POST('products.delete','ProductController@delete')->name('products.delete');


    #### Product Images ####
    Route::GET('showProductImages/{id}','ProductController@showProductImages')->name('showProductImages');
    Route::POST('deleteProductImage','ProductController@deleteProductImage')->name('deleteProductImage');
    Route::POST('addProductPhoto','ProductController@addProductPhoto')->name('addProductPhoto');


    #### orders ####
    Route::get('newOrders','OrderController@newOrders')->name('newOrders');
    Route::get('currentOrders','OrderController@currentOrders')->name('currentOrders');
    Route::get('endedOrders','OrderController@endedOrders')->name('endedOrders');
    Route::get('orderDetails/{id}','OrderController@orderDetails')->name('orderDetails');
    Route::get('orderBill/{id}','OrderController@orderBill')->name('orderBill');
    Route::POST('orders.delete','OrderController@delete')->name('orders.delete');

    #### clients ####
    Route::get('clientProfile/{id}','ClientController@clientProfile')->name('clientProfile');
    Route::get('clients','ClientController@index')->name('clients.index');
    Route::POST('delete_client','ClientController@delete')->name('delete_client');

    #### Reviews ####
    Route::group(['prefix' => 'reviews'], function () {
        Route::get('/', 'ReviewController@index')->name('reviews.index');
        Route::post('delete', 'ReviewController@delete')->name('delete_review');
    });

    #### Representatives ####
    Route::group(['prefix' => 'representatives'], function () {
        Route::get('/', 'RepresentativeController@index')->name('representative.index');
        Route::post('delete', 'RepresentativeController@delete')->name('delete_representative');
    });

    #### Provider ####
    Route::group(['prefix' => 'providers'], function () {
        Route::get('/', 'ProviderController@index')->name('provider.index');
        Route::post('delete', 'ProviderController@delete')->name('delete_provider');
        Route::get('showCommercialImages/{id}', 'ProviderController@showCommercialImages')->name('showCommercialImages');
        Route::post('deleteCommercialImage', 'ProviderController@deleteCommercialImage')->name('deleteCommercialImage');
    });


    #### Auth ####
    Route::get('logout', 'AuthController@logout')->name('admin.logout');
});



Route::get('/clear/route', function (){
    \Artisan::call('optimize:clear');
    return 'done';
});
