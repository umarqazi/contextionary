<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 * Created by PhpStorm.
 * Date: 28/08/18
 * Time: 12:16
 */

namespace App\Admin\Controllers;

use App\Context;
use App\Phrase;
use App\Repositories\IllustratorRepo;
use App\Repositories\TranslationRepo;
use App\Repositories\DefineMeaningRepo;
use App\Repositories\VoteMeaningRepo;
use App\VoteExpiry;
use App\DefineMeaning;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use Config;

class VoteExpiryController extends Controller
{

    protected $defineMeaningRepo;
    protected $illustratorRepo;
    protected $translatorRepo;
    protected $voteMeaningRepo;

    /**
     * VoteExpiryController constructor.
     */
    public function __construct()
    {
        $this->defineMeaningRepo    =   new DefineMeaningRepo();
        $this->illustratorRepo      =   new IllustratorRepo();
        $this->translatorRepo       =   new TranslationRepo();
        $this->voteMeaningRepo      =   new VoteMeaningRepo();
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Vote Expiry'));
            $content->description(trans('List'));
            $content->body($this->grid(0)->render());$content->row('<section class="content-header custom-header"><h1>'.trans('Expired Votes').'<small>List</small></h1></section>');
            $content->description(trans('List'));
            $content->body($this->grid(1)->render());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid($status)
    {
        $self= $this;
        return Admin::grid(VoteExpiry::class, function (Grid $grid) use ($status, $self){
            $grid->model()->where('status', '=', $status);
            $grid->disableExport();
            $grid->id('ID')->sortable();
            $grid->option('useWidth', true);
            $grid->column('context_id')->display(function ($context_id) {
                $context = Context::where('context_id','=',$context_id)->first();
                return $context->context_name;
            })->sortable();
            $grid->column('phrase_id')->display(function ($phrase_id) {
                $phrase = Phrase::where('phrase_id','=',$phrase_id)->first();
                if($phrase){
                    return $phrase->phrase_text;
                }
                return NULL;
            })->sortable();
            $grid->column('Total Votes')->display(function () use ($self) {
                $data = ['context_id' => $this->context_id, 'phrase_id' => $this->phrase_id, 'type' => $this->vote_type, 'vote' => '1'];
                if($this->vote_type=='translate'):
                    $data['language']=$this->language;
                endif;
                return $self->voteMeaningRepo->totalVotes($data);
            });
            $grid->vote_type()->sortable();
            $grid->disableCreateButton();
            $grid->disableExport();
            if($status == 0){
                $grid->column('expiry_date','Expiry Date')->editable('datetime')->sortable();
                $grid->column('status')->display(function ($status) {
                    return "In Progress";
                })->label('success');
            }elseif($status == 1) {
                $grid->column('expiry_date','Expiry Date')->sortable();
                $grid->column('status')->display(function ($status) {
                    return "Expired";
                })->label('danger');
            }
            $grid->filter(function ($filter){
                $filter->like('vote_type');
                $filter->like('expiry_date');
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
        return Admin::form(VoteExpiry::class, function (Form $form) use ($id) {
            $form->display('id', 'ID');
            $form->datetime('expiry_date','Expiry Date');
            $form->saved(function (Form $form) use ($id) {
                if($id){
                    admin_toastr(trans('Updated successfully!'));
                }
                return redirect(admin_base_path('auth/vote-expiry'));
            });
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
        return $this->form()->update($id);
    }

}