<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Grid;
use App\User;

class HomeController extends Controller
{
    /**
     * @var UserController
     */
    protected $user_controller;

    /**
     * @var BiddingExpiryController
     */
    protected $bidding_expiry_controller;

    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $user_controller                    = new UserController();
        $this->user_controller              = $user_controller;
        $bidding_expiry_controller          = new BiddingExpiryController();
        $this->bidding_expiry_controller    = $bidding_expiry_controller;
    }


    public function index()
    {
        $user_controller            = $this->user_controller;
        $bidding_expiry_controller  = $this->bidding_expiry_controller;
        return Admin::content(function (Content $content) use ($user_controller, $bidding_expiry_controller){
            $content->header('Dashboard');
            $content->description('Admin Dashboard');
            $content->row(
                $user_controller->userCount().
                $bidding_expiry_controller->phraseCountInDefine().
                $bidding_expiry_controller->phrasecountInIllustration().
                $bidding_expiry_controller->phraseCountInTranslation()
            );
        });
    }
    public function userListing()
    {
        return Admin::content(function (Content $content){
            $content->header('Users');
            $content->description('User Listing');
            $content->body($this->grid());
        });
    }

    protected function grid()
    {
        return Admin::grid(User::class, function (Grid $grid){
            $grid->id('ID')->sortable();
            $grid->name()->sortable();
            $grid->disableExport();
            $grid->column('email','Email');
            $grid->column('Roles')->display(function () {
                return ucfirst($this->getRoleNames()[0]);
            });
            $grid->column('created_at','Created at')->sortable();
            $grid->column('updated_at','Last Modified at')->sortable();
            $grid->filter(function ($filter){
                $filter->like('name');
                $filter->like('email');
            });

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->append('<a href="/"><i class="fa fa-eye"></i></a>');
            });
            $grid->disableRowSelector();
        });
    }


}
