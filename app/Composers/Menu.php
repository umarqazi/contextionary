<?php

View::composer('user.contributor.meaning.*', function($view)
{
    $page=['define'=>'Define', 'illustrator'=>'Illustrator', 'translator'=>'Translator'];
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

/**
 * Glossary Menus
 */
View::composer('user.user_plan.glossary.*', function($view)
{
    $page=['glossary'=>'Glossary', 'my-collection'=>'My Collection'];
    $view->with(['pageMenu'=>$page]);
});