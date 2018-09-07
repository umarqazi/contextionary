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
    protected $pageMenu;
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
        $this->pageMenu=$this->gMenu();
        return view::make('contact_us')->with(['pageMenu'=>$this->pageMenu, 'settings'=> $settings]);
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
    /**
     * fun facts menus
     */
    public function gMenu(){
        return ['funFacts'=>'Fun Facts', 'Illustrator'=>'Learning Center', 'contactUs'=>'Contact Us'];
    }
}
