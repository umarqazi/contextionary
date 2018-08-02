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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

//Route::get('/admin', 'AdminController@login')->name('Admin Login');
//Route::post('/admin/authenticate', 'AdminController@authenticate')->name('Admin Authentication');
//Route::get('/admin/dashboard', 'AdminController@index')->name('Admin Dashboard');


Route::get('profile',  'UsersController@profile')->name('profile');
Route::get('edit-profile',  'UsersController@edit')->name('edit profile');
Route::patch('update-profile', 'UsersController@update');
//->name('update profile');