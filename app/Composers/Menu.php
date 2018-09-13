<?php
    /**
     * Contributor Meanings Menu
     */
    View::composer('user.contributor.meaning.*', function($view)
    {
        $page=['define'=>'Define', 'illustrator'=>'Illustrator', 'translator'=>'Translator'];
        $view->with(['pageMenu'=>$page]);
    });

    /**
     * Vote Menus
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
    View::composer('guest_pages.*', function($view)
    {
        $page=['fun-facts'=>'Fun Facts', 'Illustrator'=>'Learning Center', 'contactUs'=>'Contact Us'];
        $view->with(['pageMenu'=>$page]);
    });
