<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactUs;
use App\Services\ContactUsService;
use Illuminate\Http\Request;
use View;
use Redirect;
use App\FunFact;

class SettingController extends Controller
{
    protected $contactUs;
    public function __construct(ContactUsService $contactUs)
    {
        $this->contactUs=$contactUs;
    }

    public function contactUs(){
        return view::make('contact_us');
    }
    public function sendMessage(ContactUs $request){
        $request->validate();
        $contactUs=$this->contactUs->saveMessage($request);
        $notification = array(
            'message' => 'Your Message has been sent to the admin. Our Respresentative will contact you soon',
            'alert_type' => 'success'
        );
        return Redirect::back()->with($notification);
    }
    public function funFacts(){
        $getFunFacts=FunFact::paginate();
        return view::make('fun_facts')->with('getFunFacts',$getFunFacts);
    }
    public function fDetail(FunFact $fact){
        return view::make('detail_fun_facts')->with('detail', $fact);
    }
}
