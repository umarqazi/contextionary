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
            Route::get('edit-roles',  'UsersController@editRoles')->name('edit roles');
            Route::get('selectPlan',  'UsersController@selectPlan')->name('selectPlan');
            Route::get('userPlan',  'UsersController@userPlan')->name('userPlan');
            Route::get('payment/{plan}',  'UsersController@showPaymentInfo')->name('payment');
            Route::post('addmoney/stripe', array('as' => 'addmoney.stripe','uses' => 'StripeController@postPaymentWithStripe'));
            Route::post('update-profile', 'UsersController@profileUpdate')->name('update-profile');
            Route::get('contributorPlan',  'ContributorController@contributorPlan')->name('contributorPlan');
            Route::post('saveContributor', 'ContributorController@saveContributor')->name('saveContributor');
            Route::group(array('prefix' => 'define', 'middleware'=>'define'), function(){
                Route::get('/',  'ContributorController@define')->name('define');
                Route::get('define-meaning/{context_id}/{phrase_id}',  'ContributorController@defineMeaning')->name('defineMeaning');
                Route::get('define-meaning/{context_id}/{phrase_id}/{id}',  'ContributorController@defineMeaning')->name('editMeaning');
            });
            Route::post('postContextMeaning',  'ContributorController@postContextMeaning')->name('postContextMeaning');
            Route::group(array('prefix' => 'coins-list'), function(){
                Route::get('/',  'ContributorController@purchaseCoins')->name('coins');
                Route::post('add-coins',  'ContributorController@addCoins')->name('addCoins');
                Route::get('add-coins',  'ContributorController@addCoins')->name('addCoins');
            });
            Route::post('applyBidding',  'ContributorController@applyBidding')->name('applyBidding');
            Route::get('start-pictionary',  'PictionaryController@index')->name('start-pictionary');
            Route::get('continue-pictionary',  'PictionaryController@continue')->name('continue-pictionary');
            Route::get('reset-pictionary',  'PictionaryController@reset')->name('reset-pictionary');
            Route::get('pictionary',  'PictionaryController@getQuestion')->name('pictionary');
            Route::post('verify-pictionary',  'PictionaryController@verifyAnswer');
            Route::get('start-spot-the-intruder',  'SpotIntruderController@index')->name('start-spot-the-intruder');
            Route::get('continue-spot-the-intruder',  'SpotIntruderController@continue')->name('continue-spot-the-intruder');
            Route::get('reset-spot-the-intruder',  'SpotIntruderController@reset')->name('reset-spot-the-intruder');
            Route::get('spot-the-intruder',  'SpotIntruderController@getQuestion')->name('spot-the-intruder');
            Route::get('glossary',  'GlossaryController@index')->name('glossary');
            Route::get('my-collection',  'GlossaryController@getListingForAuthUser')->name('my-collection');
            Route::post('add-to-fav',  'GlossaryController@addToFav');
            Route::post('remove-from-fav',  'GlossaryController@removeFromFav');
            Route::get('intruder',  'SpotIntruderController@getQuestion')->name('intruder');
            Route::post('verify-spot-the-intruder',  'SpotIntruderController@verifyAnswer');
            Route::get('tutorials',  'TutorialsController@index');

            Route::group(array('prefix' => 'phrase-list'), function(){
                Route::get('/',  'VoteController@phraseList')->name('plist');
                Route::get('vote-meaning/{context_id}/{phrase_id}',  'VoteController@voteMeaning')->name('voteMeaning');
                Route::get('poor-quality/{context_id}/{phrase_id}',  'VoteController@poorQuality')->name('poor-quality');
            });
            Route::post('vote',  'VoteController@vote')->name('vote');
            Route::group(array('prefix' => 'illustrate', 'middleware'=>'illustrator'), function(){
                Route::get('/',  'ContributorController@illustrate')->name('illustrate');
                Route::get('illustrate-meaning/{context_id}/{phrase_id}',  'ContributorController@addIllustrate')->name('addIllustrate');
                Route::post('illustrate-meaning',  'ContributorController@pAddIllustrate')->name('postIllustrate');
            });
        });
        Route::get('fun-facts',  'FunFactsController@index')->name('fun-facts');
        Route::get('fun-facts/{id}',  'FunFactsController@get');
        Route::get('contactUs',  'SettingController@contactUs')->name('contactUs');
        Route::post('contactUs',  'SettingController@sendMessage');
        Route::get('hangman',  'HangmanController@getPhrase');
    });
    Route::group(array('prefix' => 'cron'), function(){
        Route::get('meaning',  'CronController@meaningToVote')->name('meaning');
        Route::get('meaning-vote',  'CronController@checkExpiredVotes')->name('votes');
        Route::get('illustratorCron',  'CronController@illustratorBidtoVote')->name('illustratorCron');
    });
    Route::get('locale/{locale}',  'LocaleController@locale')->name('locale');
    Route::get('switchLanguage/{locale}',  'LocaleController@switchLanguage')->name('switchLanguage');
});
