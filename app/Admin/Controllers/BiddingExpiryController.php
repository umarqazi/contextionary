<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 * Created by PhpStorm.
 * Date: 28/08/18
 * Time: 12:16
 */

namespace App\Admin\Controllers;

use App\BiddingExpiry;
use App\Context;
use App\Phrase;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class BiddingExpiryController extends Controller
{

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Bidding Expiry'));
            $content->description(trans('List'));
            $content->body($this->grid(0)->render());
            $content->row('<section class="content-header custom-header"><h1>'.trans('Expired Bids').'<small>List</small></h1></section>');
            $content->description(trans('List'));
            $content->body($this->grid(1)->render());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid($status)
    {
        return Admin::grid(BiddingExpiry::class, function (Grid $grid) use ($status){
            $grid->model()->where('status', '=', $status);
            $grid->id('ID')->sortable();
            $grid->option('useWidth', true);
            $grid->column('context_id')->display(function ($context_id) {
                $context = Context::where('context_id','=',$context_id)->first();
                return $context->context_name;
            })->sortable();
            $grid->column('phrase_id')->display(function ($phrase_id) {
                $phrase = Phrase::where('phrase_id','=',$phrase_id)->first();
                return $phrase->phrase_text;
            })->sortable();
            $grid->bid_type()->sortable();
            $grid->disableCreateButton();
            $grid->disableExport();
            if($status == 0){
                $grid->column('expiry_date','Expiry Date')->editable('datetime')->sortable();
                $grid->column('status')->display(function ($status) {
                    return "In Progress";
                })->label('success');
            }elseif($status == 1) {
                $grid->column('expiry_date','Expiry Date')->sortable();
                $grid->column('status')->display(function ($status) {
                    return "Expired";
                })->label('danger');
            }
            $grid->filter(function ($filter){
                $filter->like('bid_type');
                $filter->like('expiry_date');
            });
            $grid->disableActions();
            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });
        });
    }

}