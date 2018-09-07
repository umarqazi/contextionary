<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUs;
use App\Services\ContactUsService;
use App\Setting;
use Illuminate\Http\Request;
use View;
use Redirect;

class SettingController extends Controller
{
    /**
     * @var ContactUsService
     */
    protected $contactUs;

    /**
     * SettingController constructor.
     * @param ContactUsService $contactUs
     */
    public function __construct()
    {
        $contactUs = new ContactUsService();
        $this->contactUs=$contactUs;
    }

    /**
     * @return mixed
     */
    public function contactUs(){
        $settings = Setting::all();
        return view::make('contact_us')->with('settings', $settings);
    }

    /**
     * @param ContactUs $request
     * @return mixed
     */
    public function sendMessage(ContactUs $request){
        $request->validate();
        $contactUs=$this->contactUs->saveMessage($request);
        $notification = array(
            'message' => 'Your Message has been sent to the admin. Our Respresentative will contact you soon',
            'alert_type' => 'success'
        );
        return Redirect::back()->with($notification);
    }
}
