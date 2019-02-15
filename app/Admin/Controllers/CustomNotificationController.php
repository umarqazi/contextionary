<?php

namespace App\Admin\Controllers;

use App\ContentManagement;
use App\Notification as CustomNotification;
use App\Notifications\CustomNotification as CNotification;
use App\User;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Config;
class CustomNotificationController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Custom Notifications'));
            $content->description(trans('Send'));
            $content->body($this->form());
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    public function form()
    {
        return Admin::form(CustomNotification::class, function (Form $form) {
            $form->select('sent_to', 'Send To')->options([1 => 'All Users & Contributors', 2 => 'All Contributors', 3 => 'All Users'])->rules('required')->placeholder('Send to...');;
            $form->text('subject', trans('Subject'))->rules('required')->placeholder('Enter Subject...');
            $form->hidden('sent')->default(0);
            $form->ckeditor('content', trans('Content'))->rules('required');
            $form->tools(function (Form\Tools $tools) {
                $tools->disableListButton();
            });
            $form->method('POST');
            $form->setAction ('/admin/auth/send-notifications');
            $form->saved(function () {
                return redirect(admin_base_path('auth/send-notifications'));
            });
        });
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return bool
     */
    public function store(Request $request)
    {
        if($request->sent_to == 1){
            $users  = User::all();
        }elseif($request->sent_to == 2){
            $users  = User::role(Config::get('constant.contributorRole'))->get();
        }elseif($request->sent_to == 3){
            $users  = User::role(Config::get('constant.userRole'))->get();
        }
        admin_toastr(trans('Notification Mails are been sent!'));
        return Notification::send($users, new CNotification($request->all()));
    }

}
