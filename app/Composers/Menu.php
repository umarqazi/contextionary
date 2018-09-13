<?php

View::composer(['user.contributor.meaning.*', 'user.contributor.illustrator.*'], function($view)
{
    $roles = Auth::user()->roles->pluck('name');
    foreach($roles as $role):
        $page[$role]=$role;
    endforeach;

    $view->with(['pageMenu'=>$page]);
});

/**
 * vote menus
 */
View::composer('user.contributor.votes.*', function($view)
{
    $page=['phrase-list'=>'Vote Meaning'];
    $view->with(['pageMenu'=>$page]);
});

/**
 * Transaction Menus
 */
View::composer('user.contributor.transactions.*', function($view)
{
    $page=['coins-list'=>'Purchase Coins', 'Illustrator'=>'Redeem Points', 'Translator'=>'Summary'];
    $view->with(['pageMenu'=>$page]);
});

/**
 * Guest Menus
 */
View::composer('guest_pages.*', function($view)
{
    $page=['fun-facts'=>'Fun Facts', 'Illustrator'=>'Learning Center', 'contactUs'=>'Contact Us'];
    $view->with(['pageMenu'=>$page]);
});