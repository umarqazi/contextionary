<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/30/18
 * Time: 3:16 PM
 */
use Illuminate\Support\Facades\View;
use App\Repositories\DefineMeaningRepo;
use App\Repositories\UserPointRepo;
use App\DefineMeaning;
View::composer(['layouts.*', 'user.contributor.bid'], function($view)
{
    $totalContributions='';
    $coins=0;
    $points=0;
    if(Auth::check()):
        $contributions=new DefineMeaningRepo();
        $totalContributions=$contributions->getUserContributions(Auth::user()->id);
        $coins=Auth::user()->coins;
        /* get points of login user*/
        $points=new UserPointRepo();
        $points=$points->points();
    endif;
    if($coins==NULL):
        $coins=0;
    endif;
    $view->with(['Contributions'=>$totalContributions, 'points'=>$points, 'coins'=>$coins]);
});