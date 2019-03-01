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
    $allContributions=['points'=>$types,'earning'=>$types, 'otherContributors'=>$types, 'otherContributorsRedeem'=>$types,'user_contributions'=>$types, 'user_pole_positions'=>$types, 'user_runner_up'=>$types, 'totalValueLT'=>$types, 'otherContributorsLongT'=>$types];
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
        $checkAllContribution=$pointsRepo->pointsContributions();

        $allContributions['otherContributors']=$pointsRepo->otherContributors();

        $pole=$pointsRepo->postions();

        $runnerUp=$pointsRepo->runnerUp();
        foreach($allContributions['otherContributors'] as $key=>$number){
            $allContributions['otherContributorsRedeem'][$key]  =   $userController->getEarning($number);
            $allContributions['otherContributorsLongT'][$key]  =   $userController->getHighestEarning($number);
        }
        foreach($points_group as $user_point){
            $getRedeemPoints=Auth::user()->redeemPoints->where('type', $user_point['type'])->sum('points');
            $allContributions['points'][$user_point['type']]=($user_point['sum']-$getRedeemPoints)+$allContributions['user_contributions'][$user_point['type']]*1;
            $allContributions['earning'][$user_point['type']]=$userController->getEarning($allContributions['points'][$user_point['type']]);
            $totalUserValue =   $allContributions['points'][$user_point['type']];
            $allContributions['totalValueLT'][$user_point['type']]=$userController->getHighestEarning($totalUserValue);
        }

        foreach($pole as $user_pole){
            $allContributions['user_pole_positions'][$user_pole['type']]=$user_pole['total'];
        }

        foreach($runnerUp as $user_pole){
            $allContributions['user_runner_up'][$user_pole['type']]=$user_pole['total'];
        }
        if($checkAllContribution[env('MEANING', 'meaning')]!=0){
            $allContributions['otherContributors'][env('MEANING', 'meaning')]=$allContributions['otherContributors'][env('MEANING', 'meaning')]+$contributions->getUserContributions($checkAllContribution[env('MEANING', 'meaning')]);
        }
        if($checkAllContribution[env('ILLUSTRATE', 'illustrate')]!=0){
            $allContributions['otherContributors'][env('ILLUSTRATE', 'illustrate')]=$allContributions['otherContributors'][env('ILLUSTRATE', 'illustrate')]+$illustrators->getUserContributions($checkAllContribution[env('ILLUSTRATE', 'illustrate')]);
        }
        if($checkAllContribution[env('TRANSLATE', 'translate')]!=0){
            $allContributions['otherContributors'][env('TRANSLATE', 'translate')]=$allContributions['otherContributors'][env('TRANSLATE', 'translate')]+$translationRepo->getUserContributions($checkAllContribution[env('TRANSLATE', 'translate')]);
        }
    endif;
    $view->with(['contributions'=>$allContributions, 'coins'=>$coins]);
});