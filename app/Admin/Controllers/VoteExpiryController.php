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
use App\Illustrator;
use App\Phrase;
use App\Repositories\IllustratorRepo;
use App\Repositories\TranslationRepo;
use App\Repositories\DefineMeaningRepo;
use App\Repositories\VoteMeaningRepo;
use App\Translation;
use App\VoteExpiry;
use App\DefineMeaning;
use App\VoteMeaning;
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
            $content->body($this->grid(0)->render());
        });
    }

    /**
     * @return Content
     */
    public function expiredVotes(){
        return Admin::content(function (Content $content) {
            $content->header(trans('Expired Votes'));
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
            $grid->column('context_id')->display(function ($context_id) use ($self){
                return $self->getContextName($context_id);
            })->sortable();
            $grid->column('phrase_id')->display(function ($phrase_id) use ($self) {
                return $self->getPhraseText($phrase_id);
            })->sortable();
            $grid->column('Total Votes')->display(function () use ($self) {
                $data = ['context_id' => $this->context_id, 'phrase_id' => $this->phrase_id, 'type' => $this->vote_type, 'vote' => '1'];
                if($data['type'] == 'translate'):
                    $data['language'] = $this->language;
                endif;
                return $self->getTotalVotes($data);
            });
            $grid->vote_type()->sortable();
            $grid->disableCreateButton();
            $grid->disableExport();
            if($status == 0){
                $grid->column('expiry_date','Expiry Date')->editable('datetime')->sortable();
                $grid->column('status')->display(function ($status) {
                    return "In Progress";
                })->label('success');
                $grid->disableActions();
            }elseif($status == 1) {
                $grid->column('expiry_date','Expiry Date')->sortable();
                $grid->column('status')->display(function ($status) {
                    return "Expired";
                })->label('danger');
                $grid->actions(function (Grid\Displayers\Actions $actions) {
                    $actions->disableDelete();
                    $actions->disableEdit();
                    $action = "vote-expiry/".$actions->getKey()."";
                    $actions->prepend('<a href="'.$action.'"><i class="fa fa-eye"></i></a>');
                });
            }
            $grid->filter(function ($filter){
                $filter->like('vote_type');
                $filter->like('expiry_date');
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
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Content
     */
    public function show($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('Vote Expiry');
            $content->description('Detail');
            $content->body($this->detail($id)->view($id));
        });
    }

    public function detail($id = null)
    {
        $vote_expiry    = VoteExpiry::where('id',$id)->first();
        $context_name   = $this->getContextName($vote_expiry->context_id);
        $phrase_text    = $this->getPhraseText($vote_expiry->phrase_id);
        $data           = [
                            'context_id'    => $vote_expiry->context_id,
                            'phrase_id'     => $vote_expiry->phrase_id,
                            'type'          => $vote_expiry->vote_type,
                            'vote' => '1'
                            ];
        if($data['type'] == 'translate'):
            $data['language'] = $vote_expiry->language;
        endif;
        $total_votes    = $this->getTotalVotes($data);
        $votes_data = $this->getVotesData($data);
        return Admin::form(VoteExpiry::class, function (Form $form) use ($id, $data,  $vote_expiry, $phrase_text, $context_name, $total_votes, $votes_data) {
            $form->display('id', 'ID');
            $form->display('expiry_date','Expiry Date');
            $form->display('', 'Context')->default($context_name);
            $form->display('', 'Phrase')->default($phrase_text);
            $form->display('', 'Total Votes')->default($total_votes);
            $form->display('vote_type','Type');
            if($data['type'] == 'illustrate'){
                if($votes_data['position1']){
                    $form->html('<img src="/storage/'.$votes_data['position1'].'" class="img-thumbnail">');
                    $form->display('', 'First Position Votes')->default($votes_data['position_votes1']);
                }
                if($votes_data['position2']) {
                    $form->html('<img src="/storage/' . $votes_data['position2'] . '" class="img-thumbnail">');
                    $form->display('', 'Second Position Votes')->default($votes_data['position_votes2']);
                }
                if($votes_data['position3']) {
                    $form->html('<img src="/storage/' . $votes_data['position3'] . '" class="img-thumbnail">');
                    $form->display('', 'Third Position Votes')->default($votes_data['position_votes3']);
                }
            }else{
                if($votes_data['position1']) {
                    $form->display('', 'First Position')->default($votes_data['position1']);
                    $form->display('', 'First Position Votes')->default($votes_data['position_votes1']);
                }
                if($votes_data['position2']) {
                    $form->display('', 'Second Position')->default($votes_data['position2']);
                    $form->display('', 'Second Position Votes')->default($votes_data['position_votes2']);
                }
                if($votes_data['position3']) {
                    $form->display('', 'Third Position')->default($votes_data['position3']);
                    $form->display('', 'Third Position Votes')->default($votes_data['position_votes3']);
                }
            }
            $form->disableReset();
            $form->tools(function (Form\Tools $tools) {
                $tools->disableBackButton();
                $tools->disableListButton();
                $tools->add('<a href="/admin/auth/expired-votes" class="btn btn-sm btn-default"><i class="fa fa-arrow-left"></i>&nbsp;&nbsp;Back</a>');
                $tools->add('<a href="/admin/auth/expired-votes" class="btn btn-sm btn-default"><i class="fa fa-list"></i>&nbsp;&nbsp;List</a>');
            });
        });
    }

    /**
     * @param $phrase_id
     * @return null
     */
    public function getPhraseText($phrase_id){
        $phrase         = Phrase::where('phrase_id', $phrase_id)->first();
        if($phrase){
            return $phrase->phrase_text;
        }
        return null;
    }

    /**
     * @param $context_id
     * @return null
     */
    public function getContextName($context_id){
        $context        = Context::where('context_id', $context_id)->first();
        if($context){
            return $context->context_name;
        }
        return null;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getTotalVotes($data){
        return $this->voteMeaningRepo->totalVotes($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getVotesData($data){
        $data1          = [
        'context_id'    => $data['context_id'],
        'phrase_id'     => $data['phrase_id'],
        ];
        if($data['type'] == 'meaning'){
            $model      = new DefineMeaning();
            $id_name    = 'define_meaning_id';
            $feild_name = 'meaning';
        }elseif ($data['type'] == 'illustrate'){
            $model      = new Illustrator();
            $id_name    = 'illustrator_id';
            $feild_name = 'illustrator';
        }else{
            $model      = new Translation();
            $id_name    = 'translation_id';
            $feild_name = 'translation';
        }
        $results = [];
        for ($i=1; $i <=3; $i++){
            ${'position'.$i} = $model->where($data1)->where('position', $i)->first();
            if(${'position'.$i}){
                ${'position_votes'.$i} = VoteMeaning::where($id_name, ${'position'.$i}->id)->get()->count();
            }else{
                ${'position'.$i} = $model;
                ${'position_votes'.$i} = 0;
            }
            $results['position'.$i] = ${'position'.$i}->{$feild_name};
            $results['position_votes'.$i] = ${'position_votes'.$i};
        }
        return $results;
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