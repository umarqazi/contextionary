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


use App\Services\FunFactsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class FunFactsController extends Controller
{
    /**
     * @var FunFactsService
     */
    protected $fun_facts_service;

    /**
     * FunFactsController constructor.
     */
    public function __construct()
    {
        $fun_facts_service = new FunFactsService();
        $this->fun_facts_service = $fun_facts_service;
    }

    /**
     * @return mixed
     */
    public function index(){
        $fun_facts = $this->fun_facts_service->getListing();
        return View::make('fun_facts')->with('fun_facts', $fun_facts);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function get($id){
        $fun_fact = $this->fun_facts_service->get($id);
        return View::make('detail_fun_facts')->with('fun_fact', $fun_fact);
    }
}