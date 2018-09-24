<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUs;
use App\Services\ContactUsService;
use App\Services\SettingService;
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
     * @var ContactUsService
     */
    protected $setting_service;

    protected $pageMenu;

    /**
     * SettingController constructor.
     */
    public function __construct()
    {
        $contactUs              =   new ContactUsService();
        $this->contactUs        =   $contactUs;
        $setting_service        =   new SettingService();
        $this->setting_service  =   $setting_service;
    }

    /**
     * @return mixed
     */
    public function contactUs(){
        $settings = $this->setting_service->getListing();
        return view::make('guest_pages.contact_us')->with(['settings'=> $settings]);
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
