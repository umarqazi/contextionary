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
    Route::group(['middleware' => ['locale']], function() {
        Auth::routes();
        Route::get('/', function () {
            return view('landing');
        })->name('homescreen');
        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/test', 'CronController@subscriptionCheck');
        Route::get('/test-redeem-success', 'UserdController@ts');
        Route::get('/test-redeem-cancel', 'UserdController@tc');
        Route::get('verificationEmail', 'Auth\RegisterController@sendVerificationEmail');
        Route::get('resend-email/{id}', 'Auth\RegisterController@sendVerificationEmail');
        Route::get('/verifyEmail/{token}', 'Auth\RegisterController@verifyEmail');
        Route::get('/logout', 'Auth\LoginController@logout')->name('logout');
        Route::group(['middleware' => ['auth']], function () {
            Route::get('/dashboard', 'UsersController@index')->name('dashboard')->middleware('checkUserRole');
            Route::group(['prefix' => 'profile'], function () {
                Route::get('/', 'UsersController@profile')->name('profile');
                Route::get('edit-profile', 'UsersController@edit')->name('edit-profile');
            });
            Route::get('validateRole', 'UsersController@validateRole')->name('validate');
            Route::get('selectPlan', 'UsersController@selectPlan')->name('selectPlan');
            Route::get('userPlan', 'UsersController@userPlan')->name('userPlan');
            Route::get('payment/{plan}', 'UsersController@showPaymentInfo')->name('payment');
            Route::post('addmoney/stripe', array('as' => 'addmoney.stripe', 'uses' => 'StripeController@postPaymentWithStripe'));
            Route::post('autopay', 'StripeController@postAutoPaymentWithStripe')->name('autopay');
            Route::post('cancelautopay/', 'StripeController@cancelAutoPayment')->name('cancelautopay');
            Route::post('update-profile', 'UsersController@profileUpdate')->name('update-profile');
            Route::get('contributorPlan', 'ContributorController@contributorPlan')->name('contributorPlan');
            Route::post('saveContributor', 'ContributorController@saveContributor')->name('saveContributor');

            Route::group(['middleware' => 'checkContributor'], function () {
                Route::get('edit-roles', 'UsersController@editRoles')->name('edit-roles');
                Route::group(array('prefix' => 'define', 'middleware' => 'define'), function () {
                    Route::get('/', 'ContributorController@define')->name('define');
                    Route::get('define-meaning/{context_id}/{phrase_id}', 'ContributorController@defineMeaning')->name('defineMeaning');
                    Route::get('define-meaning/{context_id}/{phrase_id}/{id}', 'ContributorController@defineMeaning')->name('editMeaning');
                });
                Route::post('postContextMeaning', 'ContributorController@postContextMeaning')->name('postContextMeaning');
                Route::group(array('prefix' => 'translate', 'middleware' => 'translate'), function () {
                    Route::get('/', 'ContributorController@translateList')->name('translate');
                    Route::get('add-translation/{context_id}/{phrase_id}', 'ContributorController@addTranslate')->name('addTranslate');
                    Route::post('add-translation', 'ContributorController@postTranslate')->name('postTranslate');
                });
                Route::post('applyBidding', 'ContributorController@applyBidding')->name('applyBidding');
                Route::group(array('prefix' => 'phrase-list', 'middleware' => 'define'), function () {
                    Route::get('/', 'VoteController@phraseList')->name('plist');
                    Route::get('vote-meaning/{context_id}/{phrase_id}', 'VoteController@voteMeaning')->name('voteMeaning');
                    Route::get('poor-quality/{context_id}/{phrase_id}/{type}', 'VoteController@poorQuality')->name('poor-quality');
                });
                Route::group(array('prefix' => 'illustrator-vote-list', 'middleware' => 'illustrator'), function () {
                    Route::get('/', 'VoteController@voteIllustrator')->name('vIllustratorList');
                    Route::get('vote-illustrator/{context_id}/{phrase_id}', 'VoteController@getSelectedIllustrators')->name('voteIllustrator');
                });
                Route::group(array('prefix' => 'translate-vote-list', 'middleware' => 'translate'), function () {
                    Route::get('/', 'VoteController@voteTranslator')->name('vTranslatorList');
                    Route::get('vote-translator/{context_id}/{phrase_id}', 'VoteController@getSelectedTranslations')->name('voteTranslator');
                    Route::post('save-translation-vote', 'VoteController@translationVote')->name('postTranslationVote');
                });
                Route::post('vote', 'VoteController@vote')->name('vote');
                Route::group(array('prefix' => 'illustrate', 'middleware' => 'illustrator'), function () {
                    Route::get('/', 'ContributorController@illustrate')->name('illustrate');
                    Route::get('illustrate-meaning/{context_id}/{phrase_id}', 'ContributorController@addIllustrate')->name('addIllustrate');
                    Route::post('illustrate-meaning', 'ContributorController@pAddIllustrate')->name('postIllustrate');
                });
                Route::post('save-illustrator-vote', 'VoteController@saveVoteIllustrator')->name('saveIllustratorVote');
                Route::group(array('prefix' => 'coins-list'), function () {
                    Route::get('/', 'ContributorController@purchaseCoins')->name('coins');
                    Route::post('add-coins', 'ContributorController@addCoins')->name('addCoins');
                    Route::get('add-coins', 'ContributorController@addCoins')->name('addCoins');
                });
                Route::get('/switchToUser', 'UsersController@switchToUser')->name('switchToUser');
                Route::get('summary',  'UsersController@summary')->name('summary');
                Route::get('redeem-points',  'UsersController@redeemPoints')->name('redeemPoints');
                Route::post('redeem-points',  'UsersController@saveEarning')->name('saveEarning');
                Route::get('redeem-all-points',  'UsersController@redeemAllPoints')->name('redeemAllPoints');
                Route::get('user-history',  'ContributorController@history')->name('history');
                Route::post('user-history/search',  'ContributorController@search')->name('search');
            });
            Route::group(['middleware' => 'checkUser'], function () {
                Route::get('/active-plan', 'UsersController@activeUserPlan')->name('activeUserPlan');
                Route::group(['middleware' => ['checkGuestUser']], function () {
                    Route::get('learning-center', 'LearningCenterController@index')->name('l-center');
                    Route::get('learning-center/explore-context', 'LearningCenterController@exploreContext')->name('explore-context');
                    Route::get('learning-center/explore-context/{context}', 'LearningCenterController@exploreContextPhrase');
                    Route::get('learning-center/explore-context/{context}/{phrase}', 'LearningCenterController@phraseDetail');
                    Route::get('learning-center/explore-context-phrase/{phrase}', 'LearningCenterController@phraseDetail2');
                    Route::get('learning-center/explore-word', 'LearningCenterController@exploreWord')->name('explore-word');
                    Route::post('learning-center/explore-word-search', 'LearningCenterController@search_word')->name('explore-word-search');
                    Route::post('learning-center/explore-context-search', 'LearningCenterController@search_context')->name('explore-context-search');
                    Route::get('start-pictionary', 'PictionaryController@index')->name('start-pictionary');
                    Route::get('continue-pictionary', 'PictionaryController@continue')->name('continue-pictionary');
                    Route::get('reset-pictionary', 'PictionaryController@reset')->name('reset-pictionary');
                    Route::get('pictionary', 'PictionaryController@getQuestion')->name('pictionary');
                    Route::post('verify-pictionary', 'PictionaryController@verifyAnswer');
                    Route::get('start-spot-the-intruder', 'SpotIntruderController@index')->name('start-spot-the-intruder');
                    Route::get('continue-spot-the-intruder', 'SpotIntruderController@continue')->name('continue-spot-the-intruder');
                    Route::get('reset-spot-the-intruder', 'SpotIntruderController@reset')->name('reset-spot-the-intruder');
                    Route::get('spot-the-intruder', 'SpotIntruderController@getQuestion')->name('spot-the-intruder');
                    Route::get('start-hangman',  'HangmanController@index')->name('start-hangman');
                    Route::get('hangman',  'HangmanController@getPhrase')->name('hangman');
                    Route::get('my-collection', 'GlossaryController@getListingForAuthUser')->name('my-collection');
                    Route::post('add-to-fav', 'GlossaryController@addToFav');
                    Route::post('remove-from-fav', 'GlossaryController@removeFromFav');
                    Route::get('intruder', 'SpotIntruderController@getQuestion')->name('intruder');
                    Route::post('verify-spot-the-intruder', 'SpotIntruderController@verifyAnswer');
                    Route::get('tutorials', 'TutorialsController@index')->name('tutorials');
                    Route::get('/switchToContributor', 'UsersController@switchToContributor')->name('switchToContributor');
                    Route::get('/delete-card/{card}', 'UsersController@deleteCard')->name('deleteCard');
                    Route::get('context-finder', 'ReadingAssistantController@contextFinder')->name('context-finder');
                    Route::post('context-finder', 'ReadingAssistantController@pContextFinder')->name('pContext-finder');
                });
            });
        });
        Route::get('glossary', 'GlossaryController@index')->name('glossary');
        Route::get('fun-facts',  'FunFactsController@index')->name('fun-facts');
        Route::get('fun-facts/{id}',  'FunFactsController@get');
        Route::get('contact-us',  'SettingController@contactUs')->name('contactUs');
        Route::post('contact-us',  'SettingController@sendMessage');
    });
    Route::group(array('prefix' => 'cron'), function(){
        Route::get('meaning',  'CronController@meaningToVote')->name('meaning');
        Route::get('meaning-vote',  'CronController@checkExpiredVotes')->name('votes');
        Route::get('illustratorCron',  'CronController@illustratorBidtoVote')->name('illustratorCron');
        Route::get('illustrator-vote',  'CronController@checkIllustratorVotes')->name('illustratorVote');
        Route::get('translate-Cron',  'CronController@translateBidtoVote')->name('TranslateCron');
        Route::get('translate-vote',  'CronController@translateVote')->name('TranslateVote');
        Route::get('check-subscription',  'CronController@subscriptionCheck')->name('subscriptionCheck');
    });
    Route::get('locale/{locale}',  'LocaleController@locale')->name('locale');
    Route::get('switchLanguage/{locale}',  'LocaleController@switchLanguage')->name('switchLanguage');
});
