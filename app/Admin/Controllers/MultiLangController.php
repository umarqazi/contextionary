<?php

namespace App\Admin\Controllers;

use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use App\User;
use Longman\LaravelMultiLang\Models\Text;


class MultiLangController extends Controller
{

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Languages'));
            $content->description(trans('Text List'));
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
        return Admin::grid(Text::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->key('Key')->sortable();
            $grid->disableExport();
            $grid->column('lang', 'Language')->sortable();
            $grid->column('value', 'Text')->sortable()->editable();
            $grid->filter(function ($filter){
                $filter->like('key');
                $filter->like('lang', 'Language');
                $filter->like('value');
            });
            $grid->disableActions();
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->disableEdit();
                $actions->disableDelete();
                $actions->disableView();
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
            $content->header('Languages');
            $content->description('Add new text');
            $content->body($this->form());
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
        return Admin::form(Text::class, function (Form $form) use ($id) {
            $form->text('key', 'Key');
            $form->radio('lang', trans('Language'))->options(['fr'=>'French', 'hi' => 'Hindi', 'en'=>'English','sp'=>'Spanish',])->rules('required');
            $form->text('value', trans('Text'))->rules('required')->placeholder('Enter Text...');
            $form->radio('scope', trans('Scope'))->options(['admin'=>'Admin', 'web' => 'Web', 'global'=>'Global',])->rules('required');
            $form->saved(function (Form $form)  use ($id){
                if($id){
                    admin_toastr(trans('Updated successfully!'));
                }else{
                    admin_toastr(trans('New Text added successfully!'));
                }
                return redirect(admin_base_path('auth/texts'));
            });
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Form
     */
    public function update($id)
    {
        return $this->form($id)->update($id);
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
        $user = Text::find($id);
        if ($user->delete()) {
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

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        return $this->form()->store();
    }

}
