<?php

namespace App\Http\Controllers;

use App\Services\GlossaryService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;

class GlossaryController extends Controller
{

    /**
     * @var GlossaryService
     */
    protected $glossary_service;

    /**
     * @var UserService
     */
    protected $user_service;

    /**
     * GlossaryController constructor.
     */
    public function __construct()
    {
        $glossary_service = new GlossaryService();
        $this->glossary_service = $glossary_service;
        $user_service = new UserService();
        $this->user_service = $user_service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $glossary_items = $this->glossary_service->getListing();
        return View::make('user.user_plan.glossary.glossary')->with('glossary_items', $glossary_items);
    }


    /**
     * Display a listing For Auth User.
     *
     * @return \Illuminate\Http\Response
     */
    public function getListingForAuthUser()
    {
        $glossary_items = $this->glossary_service->getListingForAuthUser(Auth::user());
        return View::make('user.user_plan.glossary.my_collection')->with(['glossary_items'=> $glossary_items]);
    }

    /**
     * @param Request $request
     * @return int
     */
    public function addToFav(Request $request){
        $this->glossary_service->addToFav(Auth::user(), $request->book_id);
        return 1;
    }

    /**
     * @param Request $request
     * @return int
     */
    public function removeFromFav(Request $request){
        $this->glossary_service->removeFromFav(Auth::user(), $request->book_id);
        return 1;
    }

}