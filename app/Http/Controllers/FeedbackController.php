<?php

namespace App\Http\Controllers;

use App\Mail\Feedback;
use App\Services\AdminService;
use App\Services\FeedbackService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\View;

class FeedbackController extends Controller
{

    /**
     * @var FeedbackService
     */
    protected $feedback_service;

    /**
     * @var UserService
     */
    protected $user_service;

    /**
     * @var AdminService
     */
    protected $admin_service;

    /**
     * FeedbackController constructor.
     */
    public function __construct()
    {
        $feedback_service       = new FeedbackService();
        $this->feedback_service = $feedback_service;
        $user_service           = new UserService();
        $this->user_service     = $user_service;
        $admin_service          =   new AdminService();
        $this->admin_service    =   $admin_service;
    }

    public function submit(Request $request){
        $this->feedback_service->create($request);
        Mail::to($this->admin_service->getListing()->pluck('email'))->send(new Feedback($request));
        return 1;
    }
}