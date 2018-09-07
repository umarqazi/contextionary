<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 * Date: 31/08/18
 * Time: 16:48
 */

namespace App\Admin\Controllers;

use App\SpotIntruder;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class SpotIntruderController extends Controller
{

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Spot The Intruder'));
            $content->description(trans('Questions List'));
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
        return Admin::grid(SpotIntruder::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->question()->sortable();
            $grid->answer()->sortable();
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
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('Spot The Intruder');
            $content->description('Create new question');
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
            $content->header('Spot The Intruder');
            $content->description('Edit question');
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
        return Admin::form(SpotIntruder::class, function (Form $form) use ($id) {
            $form->text('question', trans('Question'))->rules('required')->placeholder('Enter Question...');
            $form->display('id', 'ID');
            $form->text('option1', trans('Option 1'));
            $form->text('option2', trans('Option 2'));
            $form->text('option3', trans('Option 3'));
            $form->text('option4', trans('Option 4'));
            $form->radio('answer', trans('Answer'))->options([
                'option1' => 'Option 1',
                'option2' => 'Option 2',
                'option3' => 'Option 3',
                'option4' => 'Option 4',
            ]);
            $form->saved(function (Form $form) use ($id) {
                if($id){
                    admin_toastr(trans('Updated successfully!'));
                }else{
                    admin_toastr(trans('New Question created successfully!'));
                }
                return redirect(admin_base_path('auth/spot-the-intruder'));
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
            $content->header('Spot The Intruder');
            $content->description('View Question');
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
        $ques = SpotIntruder::find($id);
        if ($ques->delete()) {
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