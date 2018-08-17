<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Admin\Controllers\UserController;
use App\Services\UserService;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Grid;
use App\User;

class HomeController extends Controller
{
    protected $userService;
    protected $userController;
    public function __construct(UserService $service, UserController $userController)
    {
        $this->userService     =   $service;
        $this->userController  =   $userController;
    }

    public function index()
    {
        $usersController = $this->userController;
        return Admin::content(function (Content $content) use ($usersController){
            $content->header('Dashboard');
            $content->description('Admin Dashboard');
            $content->row($usersController->userCount().$usersController->userCount().$usersController->userCount().$usersController->userCount());
            $content->row(Dashboard::title());
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
            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });
        });
    }

}
