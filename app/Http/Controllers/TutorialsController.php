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

use App\Services\TutorialsService;
use Illuminate\Http\Request;
use View;
use Redirect;

class TutorialsController extends Controller
{


    protected $tutorials_service;

    public function __construct()
    {
        $tutorials_service          = new TutorialsService();
        $this->tutorials_service    = $tutorials_service;
    }

    /**
     * @return mixed
     */
    public function index_user()
    {
        $tutorial = $this->tutorials_service->firstUser();
        if (!empty($tutorial)) {
            $tutorial = $tutorial->content;
            return View::make('user.user_plan.reading_assistant.tutorials')->with('tutorial', $tutorial);
        } else {
            return View::make('user.user_plan.reading_assistant.tutorials')->with('tutorial', null);
        }
    }

    /**
     * @return mixed
     */
    public function index_contributor(){
        $tutorial = $this->tutorials_service->firstContributor();
        if(!empty($tutorial)){
            $tutorial = $tutorial->content;
            return View::make('user.user_plan.reading_assistant.tutorials')->with('tutorial', $tutorial);
        }else{
            return View::make('user.user_plan.reading_assistant.tutorials')->with('tutorial', null);
        }

    }

}