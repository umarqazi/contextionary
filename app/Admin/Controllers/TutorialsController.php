<?php

namespace App\Admin\Controllers;

    use \Illuminate\Database\Eloquent\Model;
    use Encore\Admin\Controllers\ModelForm;
    use Encore\Admin\Facades\Admin;
    use Encore\Admin\Form;
    use Encore\Admin\Grid;
    use Encore\Admin\Layout\Content;
    use App\User;
    use App\Tutorial;
    use Illuminate\Http\Request;

class TutorialsController extends Controller
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
            $content->header('Tutorials');
            $content->description('Edit Tutorials');
            $content->body($this->form($id)->edit($id));
        });
    }

    /**
     * Make a form builder.
     *
     * @param $id
     * @return Form
     */
    public function form($id = null)
    {
        return Admin::form(Tutorial::class, function (Form $form) use ($id) {
            $form->ckeditor('content', trans('Content'));
            $form->tools(function (Form\Tools $tools) {
                $tools->disableListButton();
            });
            $form->saved(function () use ($id) {
                if($id){
                    admin_toastr(trans('Updated successfully!'));
                }
                return redirect(admin_base_path('auth/tutorials/1'));
            });
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
        return Admin::content(function (Content $content) use ($id) {
            $content->header('Tutorials');
            $content->body($this->displayForm($id)->view($id));
        });
    }



    /**
     * Make a display form builder.
     *
     * @param $id
     *
     * @return Form
     */
    public function displayForm($id = null)
    {
        return Admin::form(Tutorial::class, function (Form $form) {
            $form->display('content', trans('Content'));
            $form->tools(function (Form\Tools $tools) {
                $tools->disableListButton();
                $tools->add('<div class="btn-group pull-right"><a class="btn btn-sm btn-default" href="/admin/auth/tutorials/1/edit"><i class="fa fa-pencil"></i>&nbsp;&nbsp;Edit</a></div>');
            });
            $form->disableSubmit();
            $form->disableReset();
        });
    }


    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function update($id)
    {
        return $this->form($id)->update($id);
    }
}
