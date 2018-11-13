<?php

namespace App\Admin\Controllers;

use App\PointsPrice;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class PointPricesController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Points Price'));
            $content->description(trans('List'));
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
            $content->header('Points Price');
            $content->description('Edit');
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
            $content->header('Points Price');
            $content->description('Create new');
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
        return Admin::grid(PointsPrice::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->disableExport();
            $grid->column('min_points', 'Min Points')->sortable();
            $grid->column('max_points', 'Max Points')->sortable();
            $grid->column('price', 'Price')->sortable();
            $grid->actions(function (Grid\Displayers\Actions $actions) {
            });
            $grid->disableRowSelector();
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
        return Admin::form(PointsPrice::class, function (Form $form) use ($id) {
            $form->display('id', 'ID');
            $form->number('min_points', 'Min Points')->rules("required|regex:/^\d+$/|min:0", [
                "regex" => "Must be numbers",
                "min"   => "Cant be less than 0",
            ]);
            $form->number('max_points', 'Max Points')->rules("required|regex:/^\d+$/|min:0", [
                "regex" => "Must be numbers",
                "min"   => "Cant be less than Min Points",
            ]);
            $form->currency('price', 'Price');
            $form->saved(function (Form $form) use ($id) {
                if($id){
                    admin_toastr(trans('Updated successfully!'));
                }else{
                    admin_toastr(trans('New Points Price created successfully!'));
                }
                return redirect(admin_base_path('auth/point-price'));
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
            $content->header('Points Price');
            $content->description('View');
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
        $point = PointsPrice::find($id);
        if ($point->delete()) {
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
