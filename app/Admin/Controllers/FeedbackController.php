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

use App\Feedback;
use App\Services\FeedbackService;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    /**
     * @var FeedbackService
     */
    protected  $feedback_services;

    /**
     * FeedbackController constructor.
     */
    public function __construct()
    {
        $feedback_services = new FeedbackService();
        $this->feedback_services = $feedback_services;
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('FeedBacks'));
            $content->description(trans('List'));
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
        return Admin::grid(Feedback::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->disableExport();
            $grid->option('useWidth', true);
            $grid->email()->sortable();
            $grid->message();
            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->column('status')->display(function ($status) {
                if($status == 0){
                    return " ";
                }else{
                    return "<i class='fa fa-check-circle' style='color: green;'></i>";
                }
            });
            $grid->column('created_at','Created at')->sortable();
            $grid->column('updated_at','Last Updated at')->sortable();
            $grid->filter(function ($filter){
                $filter->like('email');
                $filter->like('status');
            });
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableEdit();
                $action = "".$actions->getResource()."/".$actions->getKey()."";
                $actions->prepend('<a href="'.$action.'"><i class="fa fa-eye"></i></a>');
            });
            $grid->disableRowSelector();
        });
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Content
     */
    public function show($id)
    {
        $status = $this->feedback_services->status($id);
        $this->feedback_services->read($id);
        if($status == 0){
            $script = <<<SCRIPT
        var count = parseInt($('.feedback-msg-menu-item').html());
        if(count >= 2){
            $('.feedback-msg-menu-item').html(count -1);
        }
        else{
            $('.feedback-msg-menu-item').remove(); 
        }
SCRIPT;
            Admin::script($script);
        }
        return Admin::content(function (Content $content) use ($id) {
            $content->header('Feedback');
            $content->description('View Message');
            $content->body($this->form()->view($id));
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Admin::form(Feedback::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('email', trans('Email'))->rules('required');
            $form->textarea('message', trans('Message'))->rules('required');
        });
    }

    /**
     * @return mixed
     */
    public function unReadMessagesCount(){
        $unReadMessagesCount = $this->feedback_services->count();
        return $unReadMessagesCount;
    }

}