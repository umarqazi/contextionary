<?php

namespace App\Admin\Controllers;

use App\UserPoint;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;

/**
 * Class UserPointsController
 * @package App\Admin\Controllers
 */
class UserPointsController extends Controller
{
    /**
     * Add interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content){
            $content->header('User Points');
            $content->description('Add Points');
            $url = URL::previous();
            $content->body($this->form($url));
        });
    }

    /**
     * Make a form builder.
     *
     * @param $url
     * @return Form
     */
    public function form($url = null)
    {
        $current_url=explode("/",URL::current());
        $user_id    = $current_url[6];
        array_pop($current_url);
        array_push($current_url, 'store-points');
        $action_url = implode('/', $current_url);
        return Admin::form(UserPoint::class, function (Form $form) use ($url, $user_id, $action_url) {
            $form->setAction($action_url);
            $form->number('point',trans('Points') );
            $form->display('', 'Type')->value('Bonus');
            $form->hidden('url', '')->default($url);
            $form->hidden('type', '')->default('bonus');
            $form->hidden('user_id', '')->default($user_id);
            $form->ignore('url');
            $form->disableReset();
            $form->tools(function (Form\Tools $tools) use ($url) {
                $tools->disableBackButton();
                $tools->disableListButton();
                $tools->add('<a href="'.$url.'" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back</a>');
            });
            $form->saved(function () {
                admin_toastr(trans('Points added successfully!'));
                return redirect(request()->url);
            });
        });
    }

    /**
     * Store the specified resource in storage.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function store()
    {
        return $this->form()->store();
    }

}