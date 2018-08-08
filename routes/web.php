<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
MultiLang::routeGroup(function($router) {
  Route::group(['middleware' => ['locale']], function(){
    Route::get('/', function () {
      return view('index');
    })->name('homescreen');
    Auth::routes();
    Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
    Route::get('/home', 'HomeController@index')->name('home');
    Route::get('profile',  'UsersController@profile')->name('profile');
    Route::get('edit-profile',  'UsersController@edit')->name('edit profile');
    Route::get('selectPlan/{token}',  'UsersController@selectPlan')->name('selectPlan');
    Route::get('userPlan/{id}/{token}',  'UsersController@userPlan')->name('userPlan');
    Route::get('payment/{id}/{plan}/{token}',  'UsersController@showPaymentInfo')->name('payment');
    Route::post('addmoney/stripe', array('as' => 'addmoney.stripe','uses' => 'StripeController@postPaymentWithStripe'));
    Route::patch('update-profile', 'UsersController@update');
  });
  Route::get('locale/{locale}',  'LocaleController@locale')->name('locale');
});
