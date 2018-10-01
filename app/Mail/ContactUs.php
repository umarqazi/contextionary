<?php

namespace App\Mail;

use App\Http\Requests\ContactUs as ContactUsRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var ContactUs $contact_us_data
     */
    protected  $contact_us_data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ContactUsRequest $contact_us_data)
    {
        $this->contact_us_data = $contact_us_data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.contact_us_email')->with([
            'data' => $this->contact_us_data,
        ]);
    }
}
