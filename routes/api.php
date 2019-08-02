<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', 'Api\RegisterController@register');
Route::post('/login', 'Api\LoginController@login');
Route::post('/topic_list', 'Api\TopicController@topic_list');
Route::post('/contexts', 'Api\ContextController@contexts');
Route::post('/coffee_break', 'Api\CoffeeController@coffee_quotes');
Route::post('/app_version', 'Api\AppVersionController@app_version');
Route::post('/games', 'Api\GameController@games');
Route::group(['middleware' => 'auth:api'], function (){
    Route::post('/current_user', function() {
        return json('User details shown as:', 200, auth()->user());
    });
    Route::post('/context_sprint_context', 'Api\TopicController@generateContextTopics');
    Route::post('/context_sprint_phrase', 'Api\TopicController@generatePhraseTopics');
    Route::post('/clue_sprint', 'Api\ClueController@clue_sprint');
    Route::post('/context_marathon', 'Api\MarathonController@context_marathon');
    Route::post('/cross_sprint', 'Api\CrossSprintController@cross_sprint_game');
    Route::post('/update_coins', 'Api\GameController@update_coins');
});
