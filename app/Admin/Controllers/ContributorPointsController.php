<?php

namespace App\Admin\Controllers;

use App\UserPoint;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class ContributorPointsController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Contribution Winners'));
            $content->description(trans('Winners and Runner ups'));
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
        return Admin::grid(UserPoint::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->disableExport();
            $grid->option('useWidth', true);
            $grid->column('users.first_name', 'User Name')->display(function () {
                return $this->users['first_name'].' '.$this->users['last_name'];
            })->sortable();
            $grid->column('context.context_name', 'Context')->display(function () {
                return $this->context['context_name'];
            })->sortable();
            $grid->column('phrase.phrase_text', 'Phrase')->display(function () {
                return $this->phrase['phrase_text'];
            })->sortable();
            $grid->position()->sortable();
            $grid->point()->sortable();
            $grid->type()->label()->sortable();
            $grid->filter(function ($filter){
                $filter->like('users.first_name');
                $filter->like('users.last_name');
                $filter->like('context.context_name');
                $filter->like('phrase.phrase_text');
                $filter->like('type');
            });
            $grid->disableActions();
            $grid->disableRowSelector();
        });
    }
}
