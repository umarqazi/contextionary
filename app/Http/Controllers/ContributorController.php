<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/15/18
 * Time: 12:07 PM
 */

namespace App\Http\Controllers;
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

class ContributorController
{
    protected $contributor;
    protected $authService;
    protected $user;

    public function __construct(AuthService $authService, ContributorService $contributor)
    {
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
        $contextList=$this->contributor->getPaginatedContent();
        return view::make('user.contributor.define')->with(['contextList'=>$contextList, 'bucketUrl'=>$bucketURL]);
    }
    public function purchaseCoins(){
        return view::make('user.contributor.purchase_coins');
    }
    public function addCoins(Request $request){
        $coin=$request->coins;
        return view::make('user.contributor.pay_with_stripe')->with(['id'=>Auth::user()->id, 'plan'=>'1']);
    }
}