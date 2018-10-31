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
    $types=[env('MEANING')=>0,env('ILLUSTRATE')=>0,env('TRANSLATE')=>0 ];
    $allContributions=['points'=>$types,'earning'=>$types, 'otherContributors'=>$types, 'otherContributorsRedeem'=>$types,'user_contributions'=>$types, 'user_pole_positions'=>$types, 'user_runner_up'=>$types];
    if(Auth::check()):
        $contributions      =   new DefineMeaningRepo();
        $illustrators       =   new IllustratorRepo();
        $translationRepo    =   new TranslationRepo();
        $pointsRepo         =   new UserPointRepo();
        $redeem             =   new RedeemPointRepo();

        $allContributions['user_contributions'][env('MEANING')]=$contributions->getUserContributions(Auth::user()->id);
        $allContributions['user_contributions'][env('ILLUSTRATE')]=$illustrators->getUserContributions(Auth::user()->id);
        $allContributions['user_contributions'][env('TRANSLATE')]=$translationRepo->getUserContributions(Auth::user()->id);

        $coins=Auth::user()->coins;
        /* get points of login user*/
        $user_data=['user_id'=>Auth::user()->id];
        $points_group=$pointsRepo->points($user_data);

        $allContributions['otherContributors']=$pointsRepo->otherContributors();

        $pole=$pointsRepo->postions();

        $runnerUp=$pointsRepo->runnerUp();

        $allContributions['otherContributorsRedeem']=$redeem->otherContributors();

        foreach($points_group as $user_point){
            $getRedeemPoints=Auth::user()->redeemPoints->where('type', $user_point['type'])->sum('points');
            $allContributions['points'][$user_point['type']]=$user_point['sum']-$getRedeemPoints;
            $allContributions['earning'][$user_point['type']]=Auth::user()->redeemPoints->where('type', $user_point['type'])->sum('earning');
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