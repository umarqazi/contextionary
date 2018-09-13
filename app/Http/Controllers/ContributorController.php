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
                'alert_type' => 'danger'
            );
            return Redirect::back()->with($notification);
        }
    }
    public function saveContributor(Request $request){
        try{
            $records['role']=$request->role;
            $records['context']=$request->context;
            $records['language']=$request->language;
            $records['user_id']=$request->user_id;
            $this->contributor->updateContributorRecord($records);
            return Redirect::to(lang_url('dashboard'));

        }catch(\Exception $e){
            $notification = array(
                'message' => $e->getMessage(),
                'alert_type' => 'danger'
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
        return view::make('user.contributor.meaning.define')->with(['contextList'=>$contextList, 'bucketUrl'=>$bucketURL]);
    }
    public function purchaseCoins(){
        $getCoinsList=Coin::all();
        return view::make('user.contributor.transactions.purchase_coins')->with(['coins'=> $getCoinsList]);
    }
    public function addCoins(Request $request){
        $coin=$request->coins;
        $getCoinInfo=Coin::find($coin);
        return view::make('user.contributor.transactions.pay_with_stripe')->with(['id'=>Auth::user()->id, 'coin'=>$getCoinInfo]);
    }
    /*
     * define Meaning view
     */
    public function defineMeaning($context_id, $phrase_id, $id=NULL){
        $contextList=$this->contributor->getContextPhrase($context_id, $phrase_id);
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
                'alert_type' => 'danger',
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
        return view::make('user.contributor.illustrator.illustrate_list')->with('contextList',$contextList);
    }
    /** get meaning for illustrator */
    public function addIllustrate($context_id, $phrase_id){
        $contextList=$this->contributor->getContextPhrase($context_id, $phrase_id);
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
        if (Input::hasFile('illustrate')) {
            $image      = Input::file('illustrate');
            $fileName=$this->uploadImage($image, 'illustrate');
        }
        $data=['illustrator'=>$fileName, 'context_id'=>$request->context_id, 'phrase_id'=>$request->phrase_id, 'user_id'=>Auth::user()->id];
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