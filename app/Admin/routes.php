<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();
Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index');
    $router->resource('auth/simple-users', 'UserController');
    $router->resource('auth/simple-users-roles', 'RoleController');
    $router->resource('auth/simple-users-permissions', 'PermissionController');
});
