<?php

namespace App\Http\Controllers\Api;

use App\Game;
use App\InAppPurchase;
use App\Services\UserRecordService;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    private $userrecordservice;

    public function __construct()
    {
        $this->userrecordservice = new UserRecordService();
    }

    /**
     *
    @SWG\Post(
     *     path="/games",
     *     description="Games List",
     *
    @SWG\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *
     * )
     */
    public function games(){
        $games = Game::all();
        if($games){
            return json('Games are shown as:', 200, $games);
        } else{
            return json('Data not found!', 400);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function UpdateUserInfo(Request $request)
    {
        $update_info = $this->userrecordservice->UpdateUserInfo($request->all());
        if($update_info){
            return json('User info updated', 200);
        }else{
            return json('Something went wrong!', 400);
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function AppPurchases(){
        $app_puchases = InAppPurchase::select('*')->get()->groupBy('type');
        return json('In App purchases shown as:', 200, $app_puchases);
    }

    public function gameVideos()
    {
        $videos = [
            'video1' => 'http://52.43.120.48/assets/game-videos/1.mp4',
            'video2' => 'http://52.43.120.48/assets/game-videos/3.mp4',
            'video3' => 'http://52.43.120.48/assets/game-videos/9.mp4',
            'video4' => 'http://52.43.120.48/assets/game-videos/10.mp4',
            'video5' => 'http://52.43.120.48/assets/game-videos/clueSprint.mp4',
            'video6' => 'http://52.43.120.48/assets/game-videos/contextSprint.mp4',
        ];
        return json('Videos urls shown as:', 200, $videos);
    }
}
