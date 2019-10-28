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

class ContentManagementController extends Controller
{
    /**
     * @var ContentManagementService
     */
    protected $content_management_service;

    /**
     * ContentManagementController constructor.
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
        $about_us   =   '';
        $about_us   =   $this->content_management_service->find(['slug' => 'about_us']);
        if($about_us) {
            $about_us = $about_us->content;
        }
        return View::make('guest_pages.content-management')->with(['content'=>$about_us, 'title'=>'About Us']);

    }

    /**
     * @return mixed
     */
    public function privacyPolicy(){
        $privacy_policy   =   '';
        $privacy_policy   =   $this->content_management_service->find(['slug' => 'privacy_policy']);
        if($privacy_policy) {
            $privacy_policy = $privacy_policy->content;
        }
        return View::make('guest_pages.content-management')->with(['content'=>$privacy_policy, 'title'=>'Privacy Policy']);
    }

    /**
     * @return mixed
     */
    public function faq(){
        $faq   =   '';
        $faq   =   $this->content_management_service->find(['slug' => 'faq']);
        if($faq) {
            $faq = $faq->content;
        }
        return View::make('guest_pages.content-management')->with(['content'=>$faq, 'title'=>'FAQ']);

    }

}