<?php

namespace App\Admin\Controllers;

use App\Prize;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class PrizesController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Prizes'));
            $content->description(trans('Prizes List'));
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
            $content->header('Prizes');
            $content->description('Edit Prize');
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
            $content->header('Prizes');
            $content->description('Create a new prize');
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
        return Admin::grid(Prize::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->option('useWidth', true);
            $grid->image()->display(function ($image) {
                $image = explode("/", $image);
                return "<img class='img-thumbnail' src='/storage/{$image[1]}/{$image[2]}' />";
            })->setAttributes(["style" => "width:10% !important;"]);
            $grid->prize()->sortable();
            $grid->coins()->sortable();
            $grid->column('created_at','Created at')->sortable();
            $grid->column('updated_at','Last Updated at')->sortable();
            $grid->filter(function ($filter){
                $filter->like('prize');
                $filter->like('coins');
            });
            $grid->actions(function (Grid\Displayers\Actions $actions) {
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
        return Admin::form(Prize::class, function (Form $form) use ($id) {
            $form->display('id', 'ID');
            $form->image('image');
            $form->text('prize', trans('Prize'))->rules('required')->placeholder('Enter Prize...');
            $form->text('coins', trans('Coins'))->rules('required')->placeholder('Enter Coins...');
            $form->saved(function (Form $form) use ($id) {
                if($id){
                    admin_toastr(trans('Updated successfully!'));
                }else{
                    admin_toastr(trans('New Prize created successfully!'));
                }
                return redirect(admin_base_path('auth/prizes'));
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
            $content->header('Prizes');
            $content->description('View Prize');
            $content->body($this->form($id)->view($id));
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
        $package = Prize::find($id);
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
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        return $this->form()->store();
    }

//    /**
//     * Destroy data entity and remove files.
//     *
//     * @param $id
//     *
//     * @return mixed
//     */
//    public function destroy($id)
//    {
//        $ids = explode(',', $id);
//
//        foreach ($ids as $id) {
//            if (empty($id)) {
//                continue;
//            }
//            $this->deleteFilesAndImages($id);
//            $this->model->find($id)->delete();
//        }
//
//        return true;
//    }
//
//    /**
//     * Remove files or images in record.
//     *
//     * @param $id
//     */
//    protected function deleteFilesAndImages($id)
//    {
//        $data = $this->model->with($this->getRelations())
//            ->findOrFail($id)->toArray();
//
//        $this->builder->fields()->filter(function ($field) {
//            return $field instanceof Field\File;
//        })->each(function (File $file) use ($data) {
//            $file->setOriginal($data);
//
//            $file->destroy();
//        });
//    }
}
