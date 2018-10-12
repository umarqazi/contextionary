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
use App\DefineMeaning;
View::composer(['layouts.*', 'user.contributor.bid'], function($view)
{
    $totalContributions='';
    $coins=0;
    $points=[env('MEANING')=>0,env('ILLUSTRATE')=>0,env('TRANSLATE')=>0 ];
    $user_contributions=$points;
    $user_pole_positions=$points;
    $user_runner_up=$points;
    if(Auth::check()):
        $contributions=new DefineMeaningRepo();
        $user_contributions[env('MEANING')]=$contributions->getUserContributions(Auth::user()->id);
        $illustrators=new IllustratorRepo();
        $user_contributions[env('ILLUSTRATE')]=$illustrators->getUserContributions(Auth::user()->id);
        $translationRepo=new TranslationRepo();
        $user_contributions[env('TRANSLATE')]=$translationRepo->getUserContributions(Auth::user()->id);
        $coins=Auth::user()->coins;
        /* get points of login user*/
        $pointsRepo=new UserPointRepo();
        $points_group=$pointsRepo->points();
        $pole=$pointsRepo->postions();
        $runnerUp=$pointsRepo->runnerUp();
        foreach($points_group as $user_point){
            $points[$user_point['type']]=$user_point['sum'];
        }
        foreach($pole as $user_pole){
            $user_pole_positions[$user_pole['type']]=$user_pole['total'];
        }
        foreach($runnerUp as $user_pole){
            $user_runner_up[$user_pole['type']]=$user_pole['total'];
        }
    endif;
    $view->with(['contributions'=>$user_contributions, 'points'=>$points, 'coins'=>$coins, 'pole'=>$user_pole_positions, 'runnerUp'=>$user_runner_up]);
});