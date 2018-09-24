<?php

use App\Services\FeedbackService;
use App\Services\SettingService;
use Illuminate\Support\Facades\Auth;

View::composer('layouts.body_footer', function ($view) {
    $feedback=NULL;
    $settings='';
    $setting_service = new SettingService();
    $settings = $setting_service->getListing();
    $feedback_service = new FeedbackService();
    if(Auth::check()){
        $feedback = $feedback_service->getListingForAuthUser(Auth::user());
    }
    $view->with(['settings'=>$settings , 'feedback'=> $feedback ]);
});

?>