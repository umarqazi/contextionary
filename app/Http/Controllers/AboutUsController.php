<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 * Date: 04/09/18
 * Time: 16:38
 */

namespace App\Http\Controllers;

use App\Services\AboutUsService;
use Illuminate\Http\Request;
use View;
use Redirect;

class AboutUsController extends Controller
{
    /**
     * @var AboutUsService
     */
    protected $about_us_service;

    /**
     * AboutUsController constructor.
     */
    public function __construct()
    {
        $about_us_service          = new AboutUsService();
        $this->about_us_service    = $about_us_service;
    }

    /**
     * @return mixed
     */
    public function index(){
        $about_us = $this->about_us_service->first();
        if(!empty($about_us)){
            $about_us = $about_us->content;
            return View::make('guest_pages.about-us')->with('about_us', $about_us);
        }else{
            return View::make('guest_pages.about-us')->with('about_us', null);
        }

    }

}