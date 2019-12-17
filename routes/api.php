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
Route::get('/topic_list', 'Api\TopicController@topic_list');
Route::get('/contexts', 'Api\ContextController@contexts');
Route::get('/coffee_break', 'Api\CoffeeController@coffee_quotes');
Route::get('/app_version', 'Api\AppVersionController@app_version');
Route::get('/games', 'Api\GameController@games');
Route::group(['middleware' => 'auth:api'], function (){
    Route::post('/current_user', function() {
        return json('User details shown as:', 200, auth()->user());
    });
    Route::get('/context_sprint_context', 'Api\TopicController@generateContextTopics');
    Route::get('/context_sprint_phrase', 'Api\TopicController@generatePhraseTopics');
    Route::get('/clue_sprint', 'Api\ClueController@clue_sprint');
    Route::get('/context_marathon', 'Api\MarathonController@context_marathon');
    Route::get('/cross_sprint', 'Api\CrossSprintController@cross_sprint_game');
    Route::post('/marathon_statistics', 'Api\ContextMarathonStatisticController@MarathonStatistic');
    Route::post('/last_played_marathon', 'Api\ContextMarathonStatisticController@LastPlayedMarathonRecord');
    Route::post('/update_user_info', 'Api\GameController@UpdateUserInfo');
    Route::post('/attempted_questions', 'Api\UserAttemptedController@user_attempted_questions');
    Route::post('/sprint_mystery_topic', 'Api\SprintStatisticsController@SprintMysteryTopic');
    Route::post('/sprint_statistic', 'Api\SprintStatisticsController@SprintStatistic');
    Route::get('/user_game_records', 'Api\UserRecordController@UserGameRecords');
    Route::get('/user_all_statistics', 'Api\UserRecordController@UserAllStatistics');
    Route::get('/user_app_load', 'Api\UserRecordController@UserAppLoad');
    Route::get('/in_app_purchases', 'Api\GameController@AppPurchases');
    Route::post('/coin_history', 'Api\CoinHistoryController@CoinHistory');
    Route::post('/user_marathon_statistics', 'Api\UserRecordController@UserMarathonStatistics');
    Route::post('/user_sprint_statistics', 'Api\UserRecordController@UserSprintStatistics');
    Route::get('/user_game_load', 'Api\UserRecordController@UserGameLoad');
    Route::get('/super_sprint', 'Api\SuperSprintController@super_sprint');
});
