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
        Auth::routes();
        Route::get('/', function () {return view('landing');})->name('homescreen');
        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('verificationEmail/{id}', 'Auth\RegisterController@sendVerificationEmail');
        Route::get('/verifyEmail/{token}', 'Auth\RegisterController@verifyEmail');
        Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
        Route::group(['middleware'=>['auth']], function(){
            Route::get('validateRole', 'UsersController@validateRole')->name('validate');
            Route::get('/dashboard', 'UsersController@index')->name('dashboard');
            Route::get('profile',  'UsersController@profile')->name('profile');
            Route::get('edit-profile',  'UsersController@edit')->name('edit profile');
            Route::get('selectPlan',  'UsersController@selectPlan')->name('selectPlan');
            Route::get('userPlan',  'UsersController@userPlan')->name('userPlan');
            Route::get('payment/{plan}',  'UsersController@showPaymentInfo')->name('payment');
            Route::post('addmoney/stripe', array('as' => 'addmoney.stripe','uses' => 'StripeController@postPaymentWithStripe'));
            Route::post('update-profile', 'UsersController@profileUpdate')->name('update-profile');
            Route::get('contributorPlan',  'ContributorController@contributorPlan')->name('contributorPlan');
            Route::post('saveContributor', 'ContributorController@saveContributor')->name('saveContributor');
            Route::get('define',  'ContributorController@define')->name('define');
            Route::get('defineMeaning/{context_id}/{phrase_id}',  'ContributorController@defineMeaning')->name('defineMeaning');
            Route::post('postContextMeaning',  'ContributorController@postContextMeaning')->name('postContextMeaning');
            Route::get('purchaseCoins',  'ContributorController@purchaseCoins')->name('coins');
            Route::post('addCoins',  'ContributorController@addCoins')->name('addCoins');
            Route::get('addCoins',  'ContributorController@addCoins')->name('addCoins');
            Route::post('applyBidding',  'ContributorController@applyBidding')->name('applyBidding');
            Route::get('plist',  'VoteController@phraseList')->name('plist');
            Route::get('voteMeaning/{context_id}/{phrase_id}',  'VoteController@voteMeaning')->name('voteMeaning');
            Route::post('vote',  'VoteController@vote')->name('vote');
            Route::group(array('prefix' => 'cron'), function(){
                Route::get('meaning',  'CronController@meaningToVote')->name('meaning');
            });
        });
        Route::get('funFacts',  'SettingController@funFacts')->name('funFacts');
        Route::get('fDetail/{fact}',  'SettingController@fDetail')->name('fDetail');
        Route::get('contactUs',  'SettingController@contactUs')->name('contactUs');
        Route::post('contactUs',  'SettingController@sendMessage')->name('contactUs');
    });
    Route::get('locale/{locale}',  'LocaleController@locale')->name('locale');
    Route::get('switchLanguage/{locale}',  'LocaleController@switchLanguage')->name('switchLanguage');
});
