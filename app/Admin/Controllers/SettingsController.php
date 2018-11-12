<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 */


/**
 * Created by PhpStorm.
 * User: haris
 * Date: 03/09/18
 * Time: 18:32
 */

namespace App\Admin\Controllers;


use App\Setting;
use Encore\Admin\Facades\Admin;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('Settings');
            $content->description('Settings of the Website');
            $content->row('<h4>Contact Us Page Settings</h4>');
            $content->body($this->grid1()->render());
            $content->row('<h4>Contributor Settings</h4>');
            $content->body($this->grid2()->render());
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
            $content->header('Settings');
            $content->description('Change Settings');
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
            $content->header('Settings');
            $content->description('Add new keys');
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid1()
    {
        return Admin::grid(Setting::class, function (Grid $grid) {
            $grid->model()->where('type', 0);
            $grid->id('ID')->sortable();
            $grid->disableExport();
            $grid -> option('useWidth', true);
            $grid->keys()->sortable();
            $grid->values()->sortable()->editable();
            $grid->filter(function ($filter){
                $filter->like('keys');
                $filter->like('values');
            });
            $grid->disableActions();
            $grid->disableRowSelector();
        });
    }

    /**
     * @return Grid
     */
    protected function grid2()
    {
        return Admin::grid(Setting::class, function (Grid $grid) {
            $grid->model()->where('type', 1);
            $grid->id('ID')->sortable();
            $grid->disableExport();
            $grid -> option('useWidth', true);
            $grid->keys()->sortable();
            $grid->values()->sortable()->editable();
            $grid->filter(function ($filter){
                $filter->like('keys');
                $filter->like('values');
            });
            $grid->disableActions();
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
        return Admin::form(Setting::class, function (Form $form) use ($id) {
            $form->display('id', 'ID');
            $form->text('keys', trans('Key'))->rules('required')->placeholder('Enter Key...');
            $form->text('values', trans('Value'))->rules('required')->placeholder('Enter Value...');
            $form->radio('type', trans('Type'))->options(['0'=>'Contact Us Settings', '1' => 'Contributor Settings'])->rules('required');
            $form->saved(function () use ($id) {
                if($id){
                    admin_toastr(trans('Updated successfully!'));
                }else{
                    admin_toastr(trans('New key added successfully!'));
                }
                return redirect(admin_base_path('auth/settings'));
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
            $content->header('Settings');
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
        $package = Setting::find($id);
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