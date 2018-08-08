<?php

use Illuminate\Routing\Router;
        Admin::registerAuthRoutes();
        Route::group([
            'prefix'        => config('admin.route.prefix'),
            'namespace'     => config('admin.route.namespace'),
            'middleware'    => config('admin.route.middleware'),
        ], function (Router $router) {
            $router->get('/', 'HomeController@index')->name('admin_login');
            $router->resource('auth/simple-users', 'UserController');
            $router->resource('auth/simple-users-roles', 'RoleController');
            $router->resource('auth/simple-users-permissions', 'PermissionController');
            $router->resource('auth/packages', 'PackagesController');
            $router->resource('auth/points', 'PointsController');
            $router->resource('auth/texts', 'MultiLangController')->only('store', 'index', 'update','destroy','create');
            $router->resource('auth/fun-facts', 'FunFactsController');
        });
