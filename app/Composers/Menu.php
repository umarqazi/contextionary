<?php
/**
 * Contributor Meanings Menu
 */
View::composer(['user.contributor.meaning.*', 'user.contributor.illustrator.*', 'user.contributor.translation.*'], function($view)
{
    $roles = Auth::user()->roles->pluck('name');
    foreach($roles as $role):
        $page[$role]=$role;
    endforeach;
    $view->with(['pageMenu'=>$page]);
});

/**
 * Vote Menus
 */
View::composer('user.contributor.votes.*', function($view)
{
    $page=[];
    if(Auth::check()):
        if(Auth::user()->hasRole(Config::get('constant.contributorRole.define'))):
            $page['phrase-list']='Vote Meaning';
        endif;
        if(Auth::user()->hasRole(Config::get('constant.contributorRole.illustrate'))):
            $page['illustrator-vote-list']='Vote Illustrator';
        endif;
        if(Auth::user()->hasRole(Config::get('constant.contributorRole.translate'))):
            $page['translate-vote-list']='Vote Translator';
        endif;
    endif;
    $view->with(['pageMenu'=>$page]);
});

/**
 * Transaction Menus
 */
View::composer('user.contributor.transactions.*', function($view)
{
    $page=['coins-list'=>'Purchase Coins', 'redeem-points'=>'Redeem Points', 'summary'=>'Summary', 'user-history'=>'History'];
    $view->with(['pageMenu'=>$page]);
});

/**
 * Games Menus
 */
View::composer('user.user_plan.games.*', function($view)
{
    $page=['start-pictionary'=>'Pictionary', 'start-spot-the-intruder'=>'Spot The Intruder'];
    $view->with(['pageMenu'=>$page]);
});

/**
 * Guest Menus
 */
View::composer(['guest_pages.*', 'user.user_plan.learning_center.*'], function($view)
{
    $page=['fun-facts'=>'Fun Facts'];
    if(Auth::check()):
        if(Auth::user()->hasRole(Config::get('constant.userRole.premium plan'))):
            $page['learning-center']='Learning Center';
        endif;
    endif;
    $page['contact-us']='Contact Us';
    $view->with(['pageMenu'=>$page]);
});

/**
 * Glossary Menus
 */
View::composer(['user.user_plan.glossary.*'], function($view)
{
    $page=['glossary'=>'Glossary', 'my-collection'=>'My Collection'];
    $view->with(['pageMenu'=>$page]);
});


/**
 * Guest Menus
 */
View::composer(['user.profile', 'user.edit', 'user.roles'], function($view)
{
    $page=['edit-profile'=>'My Profile'];
    if(Auth::check()):
        if(Auth::user()->hasRole(Config::get('constant.contributorRole'))):
            $page['edit-roles']='Roles & Context';
        endif;
    endif;

    $view->with(['pageMenu'=>$page]);
});

/**
 * Glossary Menus
 */
View::composer(['user.user_plan.reading_assistant.*'], function($view)
{
    $page=['context-finder'=>'Context Finder','tutorials'=>'Tutorials'];
    $view->with(['pageMenu'=>$page]);
});