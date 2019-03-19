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

use App\BiddingExpiry;
use App\Context;
use App\Phrase;
use App\Services\PhraseService;
use Illuminate\Support\Facades\Storage;
use \Illuminate\Database\Eloquent\Model;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;
use App\Repositories\IllustratorRepo;
use App\Repositories\TranslationRepo;
use App\Repositories\DefineMeaningRepo;
use Config;

class BiddingExpiryController extends Controller
{
    /**
     * @var PhraseService
     */
    protected $phrase_service;


    protected $defineMeaningRepo;
    protected $illustratorRepo;
    protected $translatorRepo;

    /**
     * BiddingExpiryController constructor.
     */
    public function __construct()
    {
        $phrase_service = new PhraseService();
        $this->phrase_service = $phrase_service;
        $this->defineMeaningRepo    =   new DefineMeaningRepo();
        $this->illustratorRepo      =   new IllustratorRepo();
        $this->translatorRepo       =   new TranslationRepo();
    }

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header(trans('Bidding Expiry'));
            $content->description(trans('List'));
            $content->body($this->grid(0)->render());
        });
    }

    /**
     * @return Content
     */
    public function expiredBiddings(){
        return Admin::content(function (Content $content) {
            $content->header(trans('Expired Biddings'));
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
        return Admin::grid(BiddingExpiry::class, function (Grid $grid) use ($status,$self){
            $grid->model()->where('status', '=', $status);
            $grid->id('ID')->sortable();
            $grid->disableExport();
            $grid->option('useWidth', true);
            $grid->column('context_id')->display(function ($context_id) {
                $context = Context::where('context_id','=',$context_id)->first();
                if($context != NULL){
                    if($context->context_name != NULL){
                        return $context->context_name;
                    }else{
                        return '-';
                    }
                }else{
                    return '-';
                }
            })->sortable();
            $grid->column('phrase_id')->display(function ($phrase_id) {
                $phrase = Phrase::where('phrase_id','=',$phrase_id)->first();
                return $phrase->phrase_text;
            })->sortable();
            $grid->column('Total Bids')->display(function () use ($self) {
                $model=Config::get('constant.repo_name.'.$this->bid_type);
                if($this->bid_type == 'translate'){
                    return $self->$model->totalRecords(['context_id' => $this->context_id,'phrase_id' => $this->phrase_id, 'language' => $this->language]);
                }else{
                    return $self->$model->totalRecords(['context_id' => $this->context_id,'phrase_id' => $this->phrase_id]);
                }
            });
            $grid->bid_type()->sortable();
            $grid->column('Language')->display(function () use ($self) {
                if($this->bid_type == 'translate'){
                    return $this->language;
                }else{
                    return '-';
                }
            });
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
                $filter->like('bid_type');
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
        return Admin::form(BiddingExpiry::class, function (Form $form) use ($id) {
            $form->display('id', 'ID');
            $form->datetime('expiry_date','Expiry Date');
            $form->saved(function (Form $form) use ($id) {
                if($id){
                    admin_toastr(trans('Updated successfully!'));
                }
                return redirect(admin_base_path('auth/bidding-expiry'));
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

    /**
     * @return mixed
     */
    public function phraseCountInDefine(){
        $phrase_count  = $this->phrase_service->countInDefinePhase();
        return view('admin::dashboard.phrase_block',
            [
                'color'     => 'red',
                'label'     => 'Writer Bidding Phase',
                'value'     => $phrase_count,
                'url'       => '/admin/auth/bidding-expiry',
                'urlLabel'  => 'All Writer Bidding Phase'
            ]
        );
    }

    /**
     * @return mixed
     */
    public function phraseCountInIllustration(){
        $phrase_count  = $this->phrase_service->countInIllustrationPhase();
        return view('admin::dashboard.phrase_block',
            [
                'color'     => 'yellow',
                'label'     => 'Illustrator Bidding Phase',
                'value'     => $phrase_count,
                'url'       => '/admin/auth/bidding-expiry',
                'urlLabel'  => 'All Illustrator Bidding Phase'
            ]
        );
    }

    /**
     * @return mixed
     */
    public function phraseCountInTranslation(){
        $phrase_count  = $this->phrase_service->countInTranslationPhase();
        return view('admin::dashboard.phrase_block',
            [
                'color'     => 'green',
                'label'     => 'Translator Bidding Phase',
                'value'     => $phrase_count,
                'url'       => '/admin/auth/bidding-expiry',
                'urlLabel'  => 'All Translator Bidding Phase'
            ]
        );
    }
    /**
     * @return mixed
     */
    public function phraseCountInVoteDefine(){
        $phrase_count  = $this->phrase_service->countInDefineVotePhase();
        return view('admin::dashboard.phrase_block',
            [
                'color'     => 'red',
                'label'     => 'Writer Voting Phase',
                'value'     => $phrase_count,
                'url'       => '/admin/auth/vote-expiry',
                'urlLabel'  => 'All Writer Voting Phase'
            ]
        );
    }

    /**
     * @return mixed
     */
    public function phraseCountInVoteIllustration(){
        $phrase_count  = $this->phrase_service->countInIllustrationVotePhase();
        return view('admin::dashboard.phrase_block',
            [
                'color'     => 'yellow',
                'label'     => 'Illustrator Voting Phase',
                'value'     => $phrase_count,
                'url'       => '/admin/auth/vote-expiry',
                'urlLabel'  => 'All Illustrator Voting Phase'
            ]
        );
    }

    /**
     * @return mixed
     */
    public function phraseCountInVoteTranslation(){
        $phrase_count  = $this->phrase_service->countInTranslationVotePhase();
        return view('admin::dashboard.phrase_block',
            [
                'color'     => 'green',
                'label'     => 'Translator Voting Phase',
                'value'     => $phrase_count,
                'url'       => '/admin/auth/vote-expiry',
                'urlLabel'  => 'All Translator Voting Phase'
            ]
        );
    }
}