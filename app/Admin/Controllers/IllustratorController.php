<?php

namespace App\Admin\Controllers;

use App\Illustrator;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Spatie\Permission\Models\Role;

class IllustratorController extends Controller
{
    /**
     * Edit interface.
     *
     * @param $id
     *
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('Vote Illustration');
            $content->description('Edit Illustration');
            $url = URL::previous();
            $content->body($this->form($id, $url)->edit($id));
        });
    }

    /**
     * Make a form builder.
     *
     * @param null $id
     * @param $url
     * @return Form
     */
    public function form($id = null, $url = null)
    {
        return Admin::form(Illustrator::class, function (Form $form) use ($id, $url) {
            $form->display('id', 'ID');
            $form->image('illustrator', trans('Illustration'))->rules('required');
            $form->hidden('url', '')->default($url);
            $form->hidden('old_illustrator', '')->default(function ($form) {
                return $form->model()->illustrator;
            });
            $form->ignore('url');
            $form->disableReset();
            $form->tools(function (Form\Tools $tools) use ($url) {
                $tools->disableBackButton();
                $tools->disableListButton();
                $tools->add('<a href="'.$url.'" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back</a>');
            });

            $form->saved(function () {
                admin_toastr(trans('Updated successfully!'));
                return redirect(request()->url);
            });
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($id)
    {
        return $this->form($id)->update($id);
    }

}
