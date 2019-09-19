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
    Route::post('/marathon_statistics', 'Api\ContextMarathonStatisticController@MarathonStatistic');
    Route::post('/last_played_marathon', 'Api\ContextMarathonStatisticController@LastPlayedMarathonRecord');
    Route::post('/update_user_info', 'Api\GameController@UpdateUserInfo');
    Route::post('/attempted_questions', 'Api\UserAttemptedController@user_attempted_questions');
    Route::post('/sprint_mystery_topic', 'Api\SprintStatisticsController@SprintMysteryTopic');
    Route::post('/sprint_statistic', 'Api\SprintStatisticsController@SprintStatistic');
    Route::post('/user_game_records', 'Api\UserRecordController@UserGameRecords');
    Route::post('/user_all_statistics', 'Api\UserRecordController@UserAllStatistics');
    Route::post('/user_app_load', 'Api\UserRecordController@UserAppLoad');
    Route::post('/in_app_purchases', 'Api\GameController@AppPurchases');
});
