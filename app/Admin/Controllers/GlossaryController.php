<?php

namespace App\Admin\Controllers;

use App\Glossary;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class GlossaryController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Glossary'));
            $content->description(trans('Glossary Book List'));
            $content->body($this->grid()->render());
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
            $content->header('Glossary');
            $content->description('Edit Book');
            $content->body($this->form($id)->edit($id));
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
            $content->header('Glossary');
            $content->description('Create a new Book');
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Glossary::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid -> option('useWidth', true);
            $grid->disableExport();
            $grid->thumbnail()->display(function ($thumbnail) {
                $thumbnail= Storage::disk(config("admin.upload.disk"))->url($thumbnail);
                return "<img class='img-thumbnail' src='{$thumbnail}' />";
            })->setAttributes(["style" => "width:10% !important;"]);
            $grid->name()->sortable();
            $grid->price()->sortable();
            $grid->url();
            $grid->column('status')->display(function ($status) {
                if($status == 0){
                    return " ";
                }else{
                    return "<i class='fa fa-check-circle' style='color: green;'></i>";
                }
            });
            $grid->filter(function ($filter){
                $filter->like('name');
                $filter->like('url');
                $filter->like('price');
            });
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $action = "".$actions->getResource()."/".$actions->getKey()."";
                $actions->prepend('<a href="'.$action.'"><i class="fa fa-eye"></i></a>');
            });
            $grid->tools(function (Grid\Tools $tools) {
                $tools->batch(function (Grid\Tools\BatchActions $actions) {
                    $actions->disableDelete();
                });
            });
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
        $dir1 ='images/glossary/thumb';
        $dir2 ='images/glossary/files';
        return Admin::form(Glossary::class, function (Form $form) use ($id, $dir1, $dir2) {
            $form->display('id', 'ID');
            $form->image('thumbnail')->move($dir1)->rules('required|dimensions:width=210,height=295')->help('Note: Please upload an image of 210px*295px.');
            $form->text('name', trans('Name'))->rules('required')->placeholder('Enter Name...');
            $form->currency('price', trans('Price'))->rules('required');
            $form->file('file')->move($dir2)->rules('required');
            $form->text('url', trans('Url'))->rules('required')->placeholder('Enter URL...');
            $states = [
                'on'  => ['value' => 1, 'text' => 'Active', 'color' => 'success'],
                'off' => ['value' => 0, 'text' => 'Inactive', 'color' => 'danger'],
            ];
            $form->switch('status','Status')->states($states);
            $form->saved(function (Form $form) use ($id) {
                $thumb_name = explode('/',$form->model()->thumbnail);
                $file_name = explode('/',$form->model()->file);
                $glossary = Glossary::find($form->model()->id);
                $glossary->thumbnail = 'images/glossary/thumb/'.$thumb_name[3];
                $glossary->file = 'images/glossary/files/'.$file_name[3];
                $glossary->update();
                if($id){
                    admin_toastr(trans('Updated successfully!'));
                }else{
                    admin_toastr(trans('New Book created successfully!'));
                }
                return redirect(admin_base_path('auth/glossary'));
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
            $content->header('Glossary');
            $content->description('View Book');
            $content->body($this->form($id)->view($id));
        });
    }


    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return mixed
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
        $package = Glossary::find($id);
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