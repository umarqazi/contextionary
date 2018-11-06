<?php

namespace App\Admin\Controllers;

use App\Coin;
use App\RedeemPoint;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class RedeemController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Redeem Points'));
            $content->description(trans('Redeem Points List'));
            $content->body($this->grid()->render());
        });
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(RedeemPoint::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->disableExport();
            $grid->disableFilter();
            $grid->option('useWidth', true);
            $grid->column('users.name', 'User Name')->display(function () {
                return $this->users['first_name'].' '.$this->users['last_name'];
            });
            $grid->points();
            $grid->earning();
            $grid->column('status')->display(function ($status) {
                if($status == 0){
                    return " ";
                }else{
                    return "<i class='fa fa-check-circle' style='color: green;'></i>";
                }
            });
            $grid->column('created_at','Created at')->sortable();
            $grid->column('updated_at','Last Updated at')->sortable();
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
                $action = "redeem/".$actions->getKey()."";
                $actions->prepend('<a href="'.$action.'"><i class="fa fa-money"></i></a>');
            });
            $grid->disableRowSelector();
        });
    }
}
