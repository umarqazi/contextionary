<?php

namespace App\Http\Controllers;

use App\Services\FeedbackService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class FeedbackController extends Controller
{

    /**
     * @var GlossaryService
     */
    protected $feedback_service;

    /**
     * @var UserService
     */
    protected $user_service;

    /**
     * GlossaryController constructor.
     */
    public function __construct()
    {
        $feedback_service = new FeedbackService();
        $this->feedback_service = $feedback_service;
        $user_service = new UserService();
        $this->user_service = $user_service;
    }

    public function submit(Request $request){
        $this->feedback_service->create($request);
        return 1;
    }
}