<?php

namespace App\Mail;

use App\Services\UserService;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Feedback extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var $feedback_data
     */
    protected $feedback_data;

    /**
     * @var UserService
     */
    protected $user_service;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($feedback_data)
    {
        $user_service           =  new UserService();
        $this->user_service     =  $user_service;
        $this->feedback_data    =  $feedback_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $user = $this->user_service->get($this->feedback_data->user_id);
        return $this->view('email.feedback_email')->with([
            'data' => $this->feedback_data,
            'user'=> $user
        ]);
    }
}
