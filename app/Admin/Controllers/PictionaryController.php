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


use App\Import;
use App\Pictionary;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class PictionaryController extends Controller
{
    /**
     * @var ImportController
     */
    protected $import_controller;

    /**
     * PictionaryController constructor.
     */
    public function __construct()
    {
        $import_controller          = new ImportController();
        $this->import_controller    = $import_controller;
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Pictionary'));
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
        return Admin::grid(Pictionary::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->disableExport();
            $grid->question()->sortable();
            $grid->column('Answer')->display(function () {
                return $this->{$this->answer};
            })->sortable();
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $action = "".$actions->getResource()."/".$actions->getKey()."";
                $actions->prepend('<a href="'.$action.'"><i class="fa fa-eye"></i></a>');
            });
            $grid->tools(function (Grid\Tools $tools) {
                $tools->append("<a href='pictionary-import' class='btn btn-default btn-sm pull-right'>Import</a>");
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
            $content->header('Pictionary');
            $content->description('Create new question');
            $content->body($this->form());
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function import()
    {
        return Admin::content(function (Content $content) {
            $content->header('Pictionary');
            $content->description('Import questions');
            $content->body($this->import_controller->form('pictionary', 'pictionary' ));
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
            $content->header('Pictionary');
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
        $dir ='images/pictionary';
        return Admin::form(Pictionary::class, function (Form $form) use ($id, $dir) {
            $form->textarea('question', trans('Question'))->rules('required')->placeholder('Enter Question...');
            $form->display('id', 'ID');
            $form->image('pic1', trans('Picture 1'))->move($dir)->rules('required|mimes:jpeg,png');
            $form->image('pic2', trans('Picture 2'))->move($dir)->rules('required|mimes:jpeg,png');
            $form->image('pic3', trans('Picture 3'))->move($dir)->rules('required|mimes:jpeg,png');
            $form->image('pic4', trans('Picture 4'))->move($dir)->rules('required|mimes:jpeg,png');
            $form->text('option1', trans('Option 1'))->rules('required');
            $form->text('option2', trans('Option 2'))->rules('required');
            $form->text('option3', trans('Option 3'))->rules('required');
            $form->text('option4', trans('Option 4'))->rules('required');
            $form->radio('answer', trans('Answer'))->options([
                'option1' => 'Option 1',
                'option2' => 'Option 2',
                'option3' => 'Option 3',
                'option4' => 'Option 4',
            ])->rules('required');
            $form->saved(function (Form $form) use ($id) {
                $pic1_name = explode('/',$form->model()->pic1);
                $pic2_name = explode('/',$form->model()->pic2);
                $pic3_name = explode('/',$form->model()->pic3);
                $pic4_name = explode('/',$form->model()->pic4);
                $ques = Pictionary::find($form->model()->id);
                $ques->pic1 = 'images/pictionary/'.$pic1_name[2];
                $ques->pic2 = 'images/pictionary/'.$pic2_name[2];
                $ques->pic3 = 'images/pictionary/'.$pic3_name[2];
                $ques->pic4 = 'images/pictionary/'.$pic4_name[2];
                $ques->update();
                if($id){
                    admin_toastr(trans('Updated successfully!'));
                }else{
                    admin_toastr(trans('New Question created successfully!'));
                }
                return redirect(admin_base_path('auth/pictionary'));
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
            $content->header('Pictionary');
            $content->description('View Question');
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
        $ques = Pictionary::find($id);
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