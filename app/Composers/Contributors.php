<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/30/18
 * Time: 3:16 PM
 */
use Illuminate\Support\Facades\View;
use App\Repositories\DefineMeaningRepo;
use App\DefineMeaning;
View::composer('layouts.*', function($view)
{
    $totalContributions='';
    $coins='';
    if(Auth::check()):
        $model=new DefineMeaning();
        $contributions=new DefineMeaningRepo($model);
        $totalContributions=$contributions->getUserContributions(Auth::user()->id);
        $coins=Auth::user()->coins;
    endif;
    $view->with(['Contributions'=>$totalContributions, 'coins'=>$coins]);
});