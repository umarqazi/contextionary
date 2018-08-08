<?php

namespace App\Admin\Controllers;

use App\FunFact;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class FunFactsController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Fun Facts'));
            $content->description(trans('Fun Facts List'));
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
            $content->header('Fun Facts');
            $content->description('Edit fun fact');
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
            $content->header('Fun Facts');
            $content->description('Create a new fun fact');
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
        return Admin::grid(FunFact::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid -> option('useWidth', true);
            $grid->thumbnail()->display(function ($thumbnail) {
                $thumbnail = explode("/", $thumbnail);
                return "<img class='img-thumbnail' src='/storage/{$thumbnail[1]}/{$thumbnail[2]}' />";
            })->setAttributes(["style" => "width:10% !important;"]);
            $grid->image()->display(function ($image) {
                $image = explode("/", $image);
                return "<img class='img-thumbnail' src='/storage/{$image[1]}/{$image[2]}' />";
            })->setAttributes(["style" => "width:10% !important;"]);
            $grid->title()->sortable();
            $grid->author()->sortable();
            $grid->description();
            $grid->column('created_at','Created at')->sortable();
            $grid->column('updated_at','Last Updated at')->sortable();
            $grid->filter(function ($filter){
                $filter->like('title');
                $filter->like('author');
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
        return Admin::form(FunFact::class, function (Form $form) use ($id) {
            $form->display('id', 'ID');
            $form->image('thumbnail');
            $form->image('image');
            $form->text('title', trans('Title'))->rules('required')->placeholder('Enter Title...');
            $form->text('author', trans('Author'));
            $form->textarea('description', trans('Description'))->rules('required')->placeholder('Enter Description...');
            $form->saved(function (Form $form) use ($id) {
                if($id){
                    admin_toastr(trans('Updated successfully!'));
                }else{
                    admin_toastr($form->image());
//                    admin_toastr(trans('New Fun Fact created successfully!'));
                }
                return redirect(admin_base_path('auth/fun-facts'));
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
            $content->header('Fun Facts');
            $content->description('View fun facts');
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
        $package = FunFact::find($id);
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