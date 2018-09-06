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

class ContributorController
{
    protected $contributor;
    protected $authService;
    protected $user;
    protected $pageMenu;

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
        $this->pageMenu=$this->defineMenus();
        $bucketURL = Storage::disk('local')->url(Config::get('constant.ContextImages'));
        $contextList=$this->contributor->getAllContextPhrase();
        return view::make('user.contributor.define')->with(['contextList'=>$contextList, 'bucketUrl'=>$bucketURL, 'pageMenu'=>$this->pageMenu]);
    }
    public function purchaseCoins(){
        $this->pageMenu=$this->coinsMenu();
        $getCoinsList=Coin::all();
        return view::make('user.contributor.purchase_coins')->with(['coins'=> $getCoinsList, 'pageMenu'=>$this->pageMenu]);
    }
    public function addCoins(Request $request){
        $coin=$request->coins;
        $getCoinInfo=Coin::find($coin);
        return view::make('user.contributor.pay_with_stripe')->with(['id'=>Auth::user()->id, 'coin'=>$getCoinInfo]);
    }
    /*
     * define Meaning view
     */
    public function defineMeaning($context_id, $phrase_id){
        $this->pageMenu=$this->defineMenus();
        $contextList=$this->contributor->getContextPhrase($context_id, $phrase_id);
        return view::make('user.contributor.add_meaning')->with(['data'=>$contextList, 'pageMenu'=>$this->pageMenu]);
    }
    /*
     * add context meaning in database
     */
    public function postContextMeaning(AddContextMeaning $addContextMeaning){
        $addContextMeaning->validate();
        $meaningData=['status'=>'0','meaning'=>$addContextMeaning['meaning'], 'context_id'=>$addContextMeaning['context_id'], 'phrase_id'=>$addContextMeaning['phrase_id'],'phrase_type'=>$addContextMeaning['phrase_type'], 'user_id'=>$addContextMeaning['user_id']];
        $saveRecord=$this->contributor->saveContextMeaning($meaningData);
        $notification = array(
            'message' => 'Your Meaning has been added against phrase. You can bid now',
            'alert_type' => 'success',
            'data'=>$saveRecord
        );
        return Redirect::back()->with($notification);
    }
    /*
     * place bid against their meaning
     */
    public function applyBidding(Request $request){
        $data=['coins'=>$request->bid];
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
        $route=lang_route('define');
        return Redirect::to($route)->with($notification);
    }
    /**
     * contributors define menus
     */
    public function defineMenus(){
        return ['define'=>'Define', 'define'=>'Illustrator', 'define'=>'Translator'];
    }
    /**
     * contributor vote Menus
     */
    public function voteMenus(){
        return ['voteMeaning'=>'Vote Meaning'];
    }
    /**
     * contributor coins menus
     */
    public function coinsMenu(){
        return ['purchaseCoins'=>'Purchase Coins', 'Illustrator'=>'Redeem Points', 'Translator'=>'Summary'];
    }
}