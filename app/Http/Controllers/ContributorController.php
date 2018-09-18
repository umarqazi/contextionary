<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/15/18
 * Time: 12:07 PM
 */

namespace App\Http\Controllers;
use App\Http\Requests\AddContextMeaning;
use App\Services\ContributorService;
use Session;
use App\Services\AuthService;
use Redirect;
use View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use Illuminate\Support\Facades\Storage;
use Config;
use App\Coin;
use Input;
use Image;

class ContributorController
{
    protected $contributor;
    protected $authService;
    protected $user;

    public function __construct()
    {
        $authService=new AuthService();
        $contributor= new ContributorService();
        $this->authService = $authService;
        $this->contributor = $contributor;
    }

    public function contributorPlan(){
        try{
            $contextList=$this->contributor->getParentContextList();
            $this->user= Auth::user();
            return view::make('user.contributor.select_role')->with(['contextList'=>$contextList,'id'=> $this->user->id]);

        }catch (\Exception $e){
            $notification = array(
                'message' => $e->getMessage(),
                'alert_type' => 'error'
            );
            return Redirect::back()->with($notification);
        }
    }
    public function saveContributor(Request $request){
        try{
            $notification=[];
            $records['context']=$request->context;
            array_push($records['context'], '1','2');
            $records['role']=$request->role;
            $records['language']=$request->language;
            $records['user_id']=$request->user_id;
            $this->contributor->updateContributorRecord($records);
            if($request->profile==1):
                $notification = array(
                    'message' => 'Record has been updated',
                    'alert_type' => 'success'
                );
                $url=lang_url('profile');
            else:
                $url=lang_url('dashboard');
            endif;
            return Redirect::to($url)->with($notification);

        }catch(\Exception $e){
            $notification = array(
                'message' => $e->getMessage(),
                'alert_type' => 'error'
            );
            return Redirect::back()->with($notification);
        }
    }
    /*
     * Get context from postgres for definition
     */
    public function define(){
        $bucketURL = Storage::disk('local')->url(Config::get('constant.ContextImages'));
        $contextList=$this->contributor->getAllContextPhrase();
        $data=['route'=>'defineMeaning', 'title'=>'Phrase for Meaning'];
        return view::make('user.contributor.meaning.define')->with(['data'=>$data,'contextList'=>$contextList, 'bucketUrl'=>$bucketURL]);
    }
    public function purchaseCoins(){
        $getCoinsList=Coin::all();
        return view::make('user.contributor.transactions.purchase_coins')->with(['coins'=> $getCoinsList]);
    }
    public function addCoins(Request $request){
        $coin=$request->coins;
        if($coin == NULL):
            $notification = array(
                'message' => 'Please select the Coins Deal first',
                'alert_type' => 'error',
            );
            return Redirect::back()->with($notification);
        endif;
        $getCoinInfo=Coin::find($coin);
        return view::make('user.contributor.transactions.pay_with_stripe')->with(['id'=>Auth::user()->id, 'coin'=>$getCoinInfo]);
    }
    /*
     * define Meaning view
     */
    public function defineMeaning($context_id, $phrase_id, $id=NULL){
        $contextList=$this->contributor->getContextPhrase($context_id, $phrase_id);
        if($contextList['coins']!=NULL):
            $notification = array(
                'message' => 'Bid has been placed against this meaning',
                'alert_type' => 'error',
            );
            $url=lang_url('define');
            return Redirect::to($url)->with($notification);
        endif;
        if($id!=NULL):
            $view='user.contributor.meaning.edit_meaning';
        else:
            $view='user.contributor.meaning.add_meaning';
        endif;
        return view::make($view)->with(['data'=>$contextList]);
    }
    /*
     * add context meaning in database
     */
    public function postContextMeaning(AddContextMeaning $addContextMeaning){
        $addContextMeaning->validate();
        $id='';
        if($addContextMeaning['id']){
            $id=$addContextMeaning['id'];
        }
        $meaningData=['id'=>$id,'status'=>'0','meaning'=>$addContextMeaning['meaning'], 'context_id'=>$addContextMeaning['context_id'], 'phrase_id'=>$addContextMeaning['phrase_id'],'phrase_type'=>$addContextMeaning['phrase_type'], 'user_id'=>$addContextMeaning['user_id']];
        $saveRecord=$this->contributor->saveContextMeaning($meaningData);
        $notification = array(
            'message' => 'Your Meaning has been added against phrase. You can bid now',
            'alert_type' => 'success',
            'data'=>$saveRecord
        );
        $url=lang_url('define/define-meaning').'/'.$addContextMeaning['context_id'].'/'.$addContextMeaning['phrase_id'];
        return Redirect::to($url)->with($notification);
    }
    /*
     * place bid against their meaning
     */
    public function applyBidding(Request $request){
        $data=['coins'=>$request->bid,'context_id'=>$request->context_id,'phrase_id'=>$request->phrase_id, 'model'=>$request->model, 'type'=>$request->type];
        $updateRecord=$this->contributor->bidding($data, $request->meaning_id);
        if($updateRecord==false):
            $notification = array(
                'message' => 'Purchase coins for bidding on your meaning',
                'alert_type' => 'error',
            );
            return Redirect::back()->with($notification);
        else:
            $notification = array(
                'message' => 'Bidding has been added Successfully',
                'alert_type' => 'success',
            );
        endif;
        $route=lang_route($request->route);
        return Redirect::to($route)->with($notification);
    }

    /**
     * get phrase for illustrator
     */
    public function illustrate(){
        $contextList=$this->contributor->getIllustratePhrase();
        $data=['route'=>'addIllustrate', 'title'=>'Phrase for Illustrator'];
        return view::make('user.contributor.meaning.define')->with(['contextList'=>$contextList, 'data'=>$data]);
    }
    /** get meaning for illustrator */
    public function addIllustrate($context_id, $phrase_id){
        $contextList=$this->contributor->getMeaningForIllustrate($context_id, $phrase_id);
        $contextList['illustrator']=$this->contributor->getIllustrator($context_id, $phrase_id);
        return view::make('user.contributor.illustrator.add_illustrator')->with(['data'=>$contextList, 'illustrate'=>'1']);
    }
    /**
     * add image against meaning
     */
    public function pAddIllustrate(Request $request){
        $validators = $request->validate([
            'illustrate' => 'required|mimes:jpg,png,jpeg',
        ]);
        $id='';
        if (Input::hasFile('illustrate')) {
            $image      = Input::file('illustrate');
            $fileName=$this->uploadImage($image, 'illustrate');
        }
        if(array_key_exists('id', $request)):
            $id=$request->id;
        endif;
        $data=['id'=>$id,'illustrator'=>$fileName, 'context_id'=>$request->context_id, 'phrase_id'=>$request->phrase_id, 'user_id'=>Auth::user()->id];
        $saveIllustrate=$this->contributor->saveIllustrate($data);
        $notification = array(
            'message' => 'Your Illustrator has been added against meaning. You can bid now',
            'alert_type' => 'success',
        );
        return Redirect::back()->with($notification);

    }
    public function uploadImage($data, $place){
        $fileName   = time() . '.' . $data->getClientOriginalExtension();

        $img = Image::make($data->getRealPath());
        $img->resize(120, 120, function ($constraint) {
            $constraint->aspectRatio();
        });
        $img->stream();
        $fileName='images/'.Auth::user()->id.'/'.$place.'/'.$fileName;
        Storage::disk('public')->put($fileName, $img);
        return $fileName;
    }
}