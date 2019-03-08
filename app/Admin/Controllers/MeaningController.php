<?php

namespace App\Admin\Controllers;

use App\DefineMeaning;
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
 * Class MeaningController
 * @package App\Admin\Controllers
 */
class MeaningController extends Controller
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
            $content->header('Vote Meaning');
            $content->description('Edit Meaning');
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
        return Admin::form(DefineMeaning::class, function (Form $form) use ($id, $url) {
            $form->display('id', 'ID');
            $form->select('phrase_type',trans('Phrase Type') )->options(Config::get('constant.PhraseType'));
            $form->text('meaning', trans('Meaning'))->rules('required')->placeholder('Enter Meaning...');
            $form->hidden('url', '')->default($url);
            $form->hidden('old_meaning', '')->default(function ($form) {
                return $form->model()->meaning;
            });
            $form->hidden('old_phrase_type', '')->default(function ($form) {
                return $form->model()->phrase_type;
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