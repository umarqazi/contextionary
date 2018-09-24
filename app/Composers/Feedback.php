<?php

use App\Services\FeedbackService;
use App\Services\SettingService;
use Illuminate\Support\Facades\Auth;

View::composer('layouts.body_footer', function ($view) {
    $setting_service = new SettingService();
    $settings = $setting_service->getListing();
    $feedback_service = new FeedbackService();
    $feedback = $feedback_service->getListingForAuthUser(Auth::user());
    $view->with(['settings'=>$settings , 'feedback'=>$feedback ]);
});

?>