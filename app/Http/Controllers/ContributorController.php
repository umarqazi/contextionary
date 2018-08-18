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
}