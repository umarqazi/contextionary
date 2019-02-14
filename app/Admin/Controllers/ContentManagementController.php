<?php

namespace App\Admin\Controllers;

use App\ContentManagement;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class ContentManagementController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Pages'));
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
        return Admin::grid(ContentManagement::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->disableExport();
            $grid->option('useWidth', true);
            $grid->title()->sortable();
            $grid->slug()->sortable();
            $grid->disableExport();
            $grid->column('created_at','Created at')->sortable();
            $grid->column('updated_at','Last Updated at')->sortable();
            $grid->filter(function ($filter){
                $filter->like('email');
                $filter->like('status');
            });
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $action = "".$actions->getResource()."/".$actions->getKey()."";
                $actions->prepend('<a href="'.$action.'"><i class="fa fa-eye"></i></a>');
            });
            $grid->disableRowSelector();
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('Pages');
            $content->description('Create a new page');
            $content->body($this->form());
        });
    }

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
            $content->header('About Us');
            $content->description('Edit About Us');
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
        return Admin::form(ContentManagement::class, function (Form $form) use ($id) {
            $form->text('title', trans('Title'))->rules('required')->placeholder('Enter Title...');
            $form->text('slug', trans('Slug'))->rules('required')->placeholder('Enter Slug...');
            $form->ckeditor('content', trans('Content'))->rules('required');
            $script = <<<SCRIPT
                $("#title").keyup(function () {
                    var textValue = $(this).val();
                    textValue =textValue.replace(/ /g,"_").toLowerCase();
                    $("#slug").val(textValue);
                });
                $("#slug").keyup(function () {
                    var textValue = $(this).val();
                    textValue =textValue.replace(/ /g,"_").toLowerCase();
                    $("#slug").val(textValue);
                });
SCRIPT;
            Admin::script($script);
            $form->tools(function (Form\Tools $tools) {
                $tools->disableListButton();
            });
            $form->saved(function () use ($id) {
                if($id){
                    admin_toastr(trans('Updated successfully!'));
                }
                return redirect(admin_base_path('auth/pages'));
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
            $content->header('Pages');
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
        return Admin::form(ContentManagement::class, function (Form $form) {
            $form->display('title', trans('Title'));
            $form->display('slug', trans('Slug'));
            $form->display('content', trans('Content'));
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

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        return $this->form()->store();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $package = ContentManagement::find($id);
        if ($package->delete()) {
            admin_toastr(trans('admin.delete_succeeded'));
            return response()->json([
                'status'  => true,
                'message' => trans('admin.delete_succeeded'),
            ]);
        } else {
            return response()->json([
                'status'  => false,
                'message' => trans('admin.delete_failed'),
            ]);
        }
    }
}
