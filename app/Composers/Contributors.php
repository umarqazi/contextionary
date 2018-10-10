<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/30/18
 * Time: 3:16 PM
 */
use Illuminate\Support\Facades\View;
use App\Repositories\DefineMeaningRepo;
use App\Repositories\IllustratorRepo;
use App\Repositories\TranslationRepo;
use App\Repositories\UserPointRepo;
use App\Repositories\RedeemPointRepo;
use App\DefineMeaning;
View::composer(['layouts.*', 'user.contributor.bid', 'user.contributor.transactions.redeem-points'], function($view)
{
    $totalContributions='';
    $coins=0;
    $points=[env('MEANING')=>0,env('ILLUSTRATE')=>0,env('TRANSLATE')=>0 ];
    $earning=[env('MEANING')=>0,env('ILLUSTRATE')=>0,env('TRANSLATE')=>0 ];
    $user_contributions=$points;
    $user_pole_positions=$points;
    $user_runner_up=$points;
    if(Auth::check()):
        $contributions      =   new DefineMeaningRepo();
        $illustrators       =   new IllustratorRepo();
        $translationRepo    =   new TranslationRepo();
        $pointsRepo         =   new UserPointRepo();
        $redeem             =   new RedeemPointRepo();

        $user_contributions[env('MEANING')]=$contributions->getUserContributions(Auth::user()->id);
        $user_contributions[env('ILLUSTRATE')]=$illustrators->getUserContributions(Auth::user()->id);
        $user_contributions[env('TRANSLATE')]=$translationRepo->getUserContributions(Auth::user()->id);

        $coins=Auth::user()->coins;
        /* get points of login user*/

        $points_group=$pointsRepo->points();

        $pole=$pointsRepo->postions();

        $runnerUp=$pointsRepo->runnerUp();

        $redeemPoints=$redeem->points();

        foreach($points_group as $user_point){
            $getRedeemPoints=Auth::user()->redeemPoints->where('type', $user_point['type'])->sum('points');
            $points[$user_point['type']]=$user_point['sum']-$getRedeemPoints;
            $earning[$user_point['type']]=Auth::user()->redeemPoints->where('type', $user_point['type'])->sum('earning');
        }

        foreach($pole as $user_pole){
            $user_pole_positions[$user_pole['type']]=$user_pole['total'];
        }

        foreach($runnerUp as $user_pole){
            $user_runner_up[$user_pole['type']]=$user_pole['total'];
        }
    endif;
    $view->with(['contributions'=>$user_contributions, 'points'=>$points, 'coins'=>$coins, 'pole'=>$user_pole_positions, 'runnerUp'=>$user_runner_up, 'earning'=>$earning]);
});