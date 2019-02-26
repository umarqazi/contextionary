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
    $types=[env('MEANING', 'meaning')=>0,env('ILLUSTRATE', 'illustrate')=>0,env('TRANSLATE', 'translate')=>0 ];
    $allContributions=['points'=>$types,'earning'=>$types, 'otherContributors'=>$types, 'otherContributorsRedeem'=>$types,'user_contributions'=>$types, 'user_pole_positions'=>$types, 'user_runner_up'=>$types];
    if(Auth::check()):
        $contributions      =   new DefineMeaningRepo();
        $illustrators       =   new IllustratorRepo();
        $translationRepo    =   new TranslationRepo();
        $pointsRepo         =   new UserPointRepo();
        $redeem             =   new RedeemPointRepo();
        $userController             =   new \App\Http\Controllers\UsersController();

        $allContributions['user_contributions'][env('MEANING', 'meaning')]=$contributions->getUserContributions(Auth::user()->id);
        $allContributions['user_contributions'][env('ILLUSTRATE', 'illustrate')]=$illustrators->getUserContributions(Auth::user()->id);
        $allContributions['user_contributions'][env('TRANSLATE', 'translate')]=$translationRepo->getUserContributions(Auth::user()->id);

        $coins=Auth::user()->coins;
        /* get points of login user*/
        $user_data=['user_id'=>Auth::user()->id];
        $points_group=$pointsRepo->points($user_data);

        $allContributions['otherContributors']=$pointsRepo->otherContributors();

        $pole=$pointsRepo->postions();

        $runnerUp=$pointsRepo->runnerUp();
        foreach($allContributions['otherContributors'] as $key=>$number){
            $allContributions['otherContributorsRedeem'][$key]  =   $userController->getEarning($number);
        }
        foreach($points_group as $user_point){
            $getRedeemPoints=Auth::user()->redeemPoints->where('type', $user_point['type'])->sum('points');
            $allContributions['points'][$user_point['type']]=$user_point['sum']-$getRedeemPoints;
            $allContributions['earning'][$user_point['type']]=$userController->getEarning($allContributions['points'][$user_point['type']]);
        }

        foreach($pole as $user_pole){
            $allContributions['user_pole_positions'][$user_pole['type']]=$user_pole['total'];
        }

        foreach($runnerUp as $user_pole){
            $allContributions['user_runner_up'][$user_pole['type']]=$user_pole['total'];
        }
    
    endif;
    $view->with(['contributions'=>$allContributions, 'coins'=>$coins]);
});