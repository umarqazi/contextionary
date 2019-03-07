<?php

use Illuminate\Routing\Router;
Admin::registerAuthRoutes();
Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index')->name('admin_login');
    $router->resource('auth/users', 'AdminController');
    $router->resource('auth/simple-users', 'UserController');
    $router->resource('auth/simple-users-roles', 'RoleController');
    $router->resource('auth/simple-users-permissions', 'PermissionController');
    $router->resource('auth/texts', 'MultiLangController')->only('store', 'index', 'update','destroy','create');
    $router->resource('auth/tutorials', 'TutorialsController')->only('edit', 'update','show');
    $router->resource('auth/pages', 'ContentManagementController');
    $router->resource('auth/send-notifications', 'CustomNotificationController')->only('index','create', 'store');
    $router->resource('auth/fun-facts', 'FunFactsController');
    $router->resource('auth/settings', 'SettingsController');
    $router->resource('auth/contact-us-msgs', 'ContactUsController')->only('index', 'show');
    $router->resource('auth/feedback-msgs', 'FeedbackController')->only('index', 'show');
    $router->get('auth/pictionary-import', 'PictionaryController@import');
    $router->get('auth/spot-import', 'SpotIntruderController@import');
    $router->resource('auth/import', 'ImportController')->only( 'store', 'update');
    $router->resource('auth/pictionary', 'PictionaryController');
    $router->resource('auth/spot-the-intruder', 'SpotIntruderController');
    /* Add in Seeder*/
    $router->resource('auth/coins-deals', 'CoinsController');/*Admin Seeder Done*/
    $router->resource('auth/glossary', 'GlossaryController');/*Admin Seeder Done*/
    $router->get('auth/redeem-points/{id}', 'RedeemController@redeem');
    $router->resource('auth/point-price', 'PointPricesController');
    $router->resource('auth/packages', 'PackagesController');
    $router->resource('auth/points', 'PointsController');
    $router->resource('auth/redeem', 'RedeemController');
    $router->resource('auth/vote-expiry', 'VoteExpiryController')->only('index', 'show', 'update');
    $router->get('auth/expired-votes', 'VoteExpiryController@expiredVotes');
    $router->resource('auth/bidding-expiry', 'BiddingExpiryController')->only('index', 'show', 'update');
    $router->resource('auth/meaning', 'MeaningController')->only('edit', 'update');
    $router->resource('auth/translate', 'TranslationController')->only('edit', 'update');
    $router->resource('auth/illustrate', 'IllustratorController')->only('edit', 'update');
    $router->get('auth/expired-biddings', 'BiddingExpiryController@expiredBiddings');
    $router->get('auth/contribution-winner', 'ContributorPointsController@index');
});