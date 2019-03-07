<?php

namespace App\Admin\Controllers;

use App\ContentManagement;
use App\CustomNotification;
use App\User;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class SendNotificationController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function send()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Send Notifications'));
            $content->body($this->form());
        });
    }

    /**
     * Make a form builder.
     *
     */
    public function form()
    {
        return Admin::content( function (Content $content){
            $form = new Form();
            $form->method('post');
            $form->select('sent_to', 'Send To')->options(function () {
                return User::all()->pluck('full_name', 'id');
            })->rules('required')->placeholder('Send to...');;
            $form->text('subject', trans('Subject'))->rules('required')->placeholder('Enter Subject...');
            $form->hidden('sent')->default(0);
            $form->ckeditor('content', trans('Content'))->rules('required');
            $form->tools(function (Form\Tools $tools) {
                $tools->disableListButton();
            });
            $form->saved(function () {
                admin_toastr(trans('Notification are Queued for being Sent!'));
            });
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        return $this->form()->store();
    }
}
