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
use App\SpotIntruder;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    /**
     * @var Pictionary
     */
    protected $pictionary;

    /**
     * @var SpotIntruder
     */
    protected $spot_the_intruder;

    /**
     * ImportController constructor.
     */
    public function __construct()
    {
        $pictionary                 = new Pictionary();
        $this->pictionary           = $pictionary;
        $spot_the_intruder          = new SpotIntruder();
        $this->spot_the_intruder    = $spot_the_intruder;
    }

    /**
     * Make a form builder.
     * @param $model_name, $route
     * @return Form
     */
    public function form($model_name = NULL, $route = NULL)
    {
        $dir ='images/imports/files';
        return Admin::form(Import::class, function (Form $form) use ($model_name, $dir, $route){
            $form->setAction('import');
            $form->file('file', trans('CSV File'))->move($dir)->rules('required');
            $form->hidden('type', 'Type')->default('Pictionary');
            $form->hidden('model')->default($model_name);
            $form->hidden('route')->default($route);
            $form->ignore(['model', 'route']);
            $form->saved(function (Form $form) {
                $file_name = explode('/',$form->model()->file);
                $import = Import::find($form->model()->id);
                $import->file = 'images/imports/files/'.$file_name[3];
                $import->update();
                $file = public_path('storage/'.$import->file);
                $recordsArr = $this->csvToArray($file);
                $this->{request()->model}->insert($recordsArr);
                admin_toastr(trans('New records inserted successfully!'));
                return redirect(admin_base_path('auth/'.request()->route));
            });
        });
    }

    public function csvToArray($filename = '', $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)){
            return false;
        }
        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        return $data;
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