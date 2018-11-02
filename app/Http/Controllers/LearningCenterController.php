<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 * Date: 03/09/18
 * Time: 16:06
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class LearningCenterController extends Controller
{
    /**
     * @return mixed
     */
    public function index(){
        return View::make('guest_pages.learning_center');
    }
}