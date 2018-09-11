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

use App\Tutorial;
use Illuminate\Http\Request;
use View;
use Redirect;

class TutorialsController extends Controller
{

    public function index(){
        $tutorial = Tutorial::first()->content;
        return View::make('tutorials')->with('tutorial', $tutorial);
    }

}