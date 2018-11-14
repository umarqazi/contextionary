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

use App\ContactUs;
use Illuminate\Support\Facades\Storage;
use App\Services\ContactUsService;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    /**
     * @var ContactUsService
     */
    protected  $contactUsServices;

    /**
     * ContactUsController constructor.
     */
    public function __construct()
    {
        $contactUsServices = new ContactUsService;
        $this->contactUsServices = $contactUsServices;
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Contact Us Messages'));
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
        return Admin::grid(ContactUs::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->disableExport();
            $grid->option('useWidth', true);
            $grid->first_name()->sortable();
            $grid->last_name()->sortable();
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
            $grid->filter(function ($filter){
                $filter->like('first_name');
                $filter->like('last_name');
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
        $status = $this->contactUsServices->status($id);
        $this->contactUsServices->read($id);
        if($status == 0){
            $script = <<<SCRIPT
        var count = parseInt($('.contact-msg-menu-item').html());
        if(count >= 2){
            $('.contact-msg-menu-item').html(count -1);
        }
        else{
            $('.contact-msg-menu-item').remove(); 
        }
SCRIPT;
            Admin::script($script);
        }
        return Admin::content(function (Content $content) use ($id) {
            $content->header('Contact Us Messages');
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
        return Admin::form(ContactUs::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('first_name', trans('First Name'))->rules('required')->placeholder('Enter First Name...');
            $form->text('last_name', trans('Last Name'))->rules('required')->placeholder('Enter Last Name...');
            $form->text('email', trans('Email'))->rules('required')->placeholder('Enter Email...');
            $form->textarea('message', trans('Message'))->rules('required')->placeholder('Enter Message...');
        });
    }

    /**
     * @return mixed
     */
    public function unReadMessagesCount(){
        $unReadMessagesCount = $this->contactUsServices->count();
        return $unReadMessagesCount;
    }

}