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

use App\Services\ContentManagementService;
use Illuminate\Http\Request;
use View;
use Redirect;

class AboutUsController extends Controller
{
    /**
     * @var ContentManagementService
     */
    protected $content_management_service;

    /**
     * AboutUsController constructor.
     */
    public function __construct()
    {
        $content_management_service         = new ContentManagementService();
        $this->content_management_service   = $content_management_service;
    }

    /**
     * @return mixed
     */
    public function index(){
        $about_us = $this->content_management_service->find(['slug' => 'about_us']);
        if(!empty($about_us)){
            $about_us = $about_us->content;
            return View::make('guest_pages.about-us')->with('about_us', $about_us);
        }else{
            return View::make('guest_pages.about-us')->with('about_us', null);
        }

    }

}