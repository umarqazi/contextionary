<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Mail\RedeemPoint;
use App\Profile;
use App\Repositories\UserPointRepo;
use App\Services\AuthService;
use App\Services\ContributorService;
use App\Services\MutualService;
use App\Services\RoleService;
use App\Services\TransactionService;
use App\Services\UserService;
use App\User;
use App\PointsPrice;
use App\UserCard;
use Carbon;
use Config;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use View;
use Mail;

class UsersController extends Controller
{

    use ModelForm;
    protected $userServices;
    protected $userRoles;
    protected $authService;
    protected $user;
    protected $register;
    protected $contributorService;
    protected $transactions;
    protected $user_points;
    /**
     * UsersController constructor.
     */
    public function __construct( )
    {
        $this->userServices         =   new UserService();
        $this->userRoles            =   new RoleService();
        $this->authService          =   new AuthService();
        $this->register             =   new RegisterController();
        $this->mutualService        =   new MutualService();
        $this->contributorService   =   new ContributorService();
        $this->transactions         =   new TransactionService();
        $this->user_points          =   new UserPointRepo();
    }

    /**
     * @return mixed
     */
    public function validateRole(){
        if(Auth::check()){
            $userRole=$this->userRoles->checkRole(Auth::user()->id);
            if(!$userRole->isEmpty()){
                return Redirect::to(lang_route('dashboard'));
            }else{
                return Redirect::to(lang_route('selectPlan'));
            }
        }

    }

    /**
     * @return mixed
     */
    public function index(){
        return view::make('home');
    }

    /**
     * @return View
     */
    public function userCount(){
        $countUsers = $this->userServices->countUsers();
        return view('admin::dashboard.blocks',
            [
                'value'      => $countUsers,
                'label'      => 'Users',
                'url'        => '#url',
                'urlLabel'   => 'All Users'
            ]
        );
    }

    /**
     * @return mixed
     */
    public function edit(){
        $user = Auth::user();
        return view::make('user.edit')->with('user', $user);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function profileUpdate(Request $request)
    {
        // validate
        $rules = array(
            'first_name'       => 'required',
            'last_name'       => 'required',
            'email'      => 'required|email',
            'password'   => 'nullable|min:6|confirmed',
            'timezone'   => 'required'
        );
        $validator = Validator::make(Input::all(), $rules);
        // process the login
        if ($validator->fails()) {
            return Redirect::to(lang_route('edit-profile'))
                ->withErrors($validator)
                ->withInput(Input::except('password'));
        } else {
            // update
            $user = Auth::user();
            $user->first_name       = strip_tags(Input::get('first_name'));
            $user->last_name       = strip_tags(Input::get('last_name'));
            $user->email      = strip_tags(Input::get('email'));
            $fileName=$user->profile_image;
            if(Input::get('password') != ''){
                $user->password = bcrypt(Input::get('password'));
            }

            if (Input::hasFile('profile_image')) {
                $image      = Input::file('profile_image');
                $fileName=$this->register->uploadImage($image, $user->id);
            }
            $user->profile_image=$fileName;
            $user->timezone=strip_tags(Input::get('timezone'));
            $user->save();
            $updateProfile=Profile::where('user_id', $user->id)->first();
            $updateProfile->phone_number=strip_tags(Input::get('phone_number'));
            $updateProfile->gender=strip_tags(Input::get('gender'));
            $updateProfile->country=strip_tags(Input::get('country'));
            $updateProfile->date_birth=strip_tags(Input::get('date_birth'));
            $updateProfile->native_language=strip_tags(Input::get('native_language'));
            $updateProfile->pseudonyme=strip_tags(Input::get('pseudonyme'));
            $updateProfile->bio=strip_tags(Input::get('bio'));
            $updateProfile->save();
            // redirect
            $notification = array(
                'message' => trans('content.profile_updated'),
                'alert_type' => 'success'
            );
            return Redirect::to(lang_url('profile'))->with($notification);
        }
    }

    /**
     * @return View
     */
    public function profile()
    {
        $user = Auth::user();
        $user = User::with('profile')->find($user->id);
        return view('user.profile', compact('user'));
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        // delete
        $user = User::find($id);
        $user->delete();

        // redirect
        Session::flash('message', trans('content.user_deleted'));
        return back();
    }

    /**
     * @return mixed
     */
    public function selectPlan(){
        $this->user = Auth::user();
        return view::make('user.user_plan.select_plan')->with(['user'=>$this->user]);
    }

    /**
     * @return mixed
     */
    public function userPlan(){
        $this->user = Auth::user();
        return view::make('user.user_plan.user_plan')->with(['id'=> $this->user->id]);
    }

    /**
     * @param $plan
     * @return mixed
     */
    public function showPaymentInfo($plan){
        $this->user = Auth::user();
        return view::make('user.user_plan.pay_with_stripe')->with(['id'=>$this->user->id, 'plan'=>$plan]);
    }
    /**
     * @return mixed
     * get roles of user
     */
    public function editRoles(){
        $user=Auth::user();
        $roles=$user->getRoleNames();
        $contributor=$this->contributorService->getParentContextList();
        $familiarContext=$this->mutualService->getUserContext($user->id);
        foreach($contributor as $key=>$context):
            foreach($familiarContext as $familiar):
                if($familiar['context_id']==$context['context_id']):
                    $contributor[$key]['selected']=$familiar['context_id'];
                endif;
            endforeach;
        endforeach;
        $contributorRoles=Config::get('constant.contributorRole');
        $userRoles=[];
        $i=0;
        foreach($contributorRoles as $role):
            $userRoles[$i]['role']=$role;
            $userRoles[$i]['selected']='';
            foreach($roles as $currentRole):
                if($role==$currentRole):
                    $userRoles[$i]['selected']=$currentRole;
                endif;
            endforeach;
            $i++;
        endforeach;
        $language=$user->profile->language_proficiency;
        return view::make('user.roles')->with(['roles'=>$userRoles,'language'=>$language, 'contextList'=>$contributor, 'id'=>$user->id, 'familiar_context'=>$familiarContext]);
    }

    /**
     * @return mixed
     */
    public function switchToContributor(){
        $roles=Auth::user()->contributor_roles;
        if($roles==NULL):
            return Redirect::to(lang_url('contributorPlan'));
        endif;
        $roles=explode(',',Auth::user()->contributor_roles);
        $this->userRoles->assignRoleToUser(Auth::user()->id, $roles);
        return Redirect::to(lang_url('dashboard'));
    }

    /**
     * @return mixed
     */
    public function switchToUser(){
        if(Auth::user()->hasRole(Config::get('constant.contributorRole'))):
            if(Auth::user()->user_roles==NULL):
                $writer=Auth::user()->defineMeaning->whereIn('status', ['1','3'])->count();
                $illustrator=Auth::user()->illustrator->whereIn('status', ['1','3'])->count();
                $translation=Auth::user()->defineMeaning->whereIn('status', ['1','3'])->count();
                $totalContributions=$writer+$illustrator+$translation;
                if($totalContributions <= 0):
                    return Redirect::to(lang_url('userPlan'));
                elseif($totalContributions >= 1 && $totalContributions <= 9):
                    $roles=Config::get('constant.userRole.basic plan');
                elseif($totalContributions >= 10 && $totalContributions <= 19):
                    $roles=Config::get('constant.userRole.advance plan');
                elseif($totalContributions >= 20):
                    $roles=Config::get('constant.userRole.premium plan');
                endif;
            else:
                $roles=Config::get('constant.packages.'.Auth::user()->user_roles);
            endif;
            $this->userRoles->assignRoleToUser(Auth::user()->id, $roles);
        endif;
        return Redirect::to(lang_url('dashboard'));
    }

    public function summary(){
        $transaction=Auth::user()->transaction;
        $redeemPoints=Auth::user()->redeemPoints;
        $transationRecord=[];
        foreach($transaction as $key=>$record){
            $transationRecord[$key]['created_at']=Carbon::parse($record['created_at'])->format('d/m/Y');
            $transationRecord[$key]['coins']=$record['coins'];
            $transationRecord[$key]['amount']=$record['amount'];
            $transationRecord[$key]['purchase_type']=$record['purchase_type'];
            $transationRecord[$key]['role']='';
        }
        foreach($redeemPoints as $key=>$record){
            $transationRecord[$key]['created_at']=Carbon::parse($record['created_at'])->format('d/m/Y');
            $transationRecord[$key]['coins']=$record['points'];
            $transationRecord[$key]['amount']=$record['earning'];
            $transationRecord[$key]['purchase_type']='redeem_point';
            $transationRecord[$key]['role']=$record['type'];
        }
        $transationRecord=array_reverse($transationRecord);
        $records=$this->mutualService->paginatedRecord($transationRecord, 'summary');
        return view::make('user.contributor.transactions.summary')->with('transactions', $records);
    }

    /**
     * @return mixed
     */
    public function redeemPoints(Request $request){
        $modal=$request->modal;
        $prices=PointsPrice::all();
        return view::make('user.contributor.transactions.redeem-points')->with(['modal'=> $modal, 'pointsPrices'=>$prices]);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function saveEarning(Request $request){
        $point=Auth::user()->userPoints->where('type', $request->type)->sum('point');
        $earning=Auth::user()->redeemPoints->where('type', $request->type)->sum('points');
        $reamaining=$point-$earning;
        $validators = Validator::make($request->all(), [
            'type'       => 'required',
            'points'     => 'numeric|min:10|max:'.$reamaining,
        ]);
        if ($validators->fails()) {
            return redirect::to(lang_url('redeem-points'))
                ->withErrors($validators)
                ->withInput()->with('modal', '1');
        }
        $earning=0;
        $earning=$this->createPoint(strip_tags($request->points), $request->type);
        $notification = array(
            'message' => trans('content.redeem_points'),
            'alert_type' => 'success'
        );
        $email_data=['first_name'=>Auth::user()->first_name, 'last_name'=>Auth::user()->last_name, 'points'=>$request->points, 'earning'=>$earning];
        Mail::to(Auth::user()->email)->send(new RedeemPoint($email_data));
        return Redirect::to(lang_url('redeem-points'))->with($notification);
    }

    /**
     * @return mixed
     */
    public function activeUserPlan(){
        $role=Auth::user()->getRoleNames();
        $currentDate=Carbon::now();
        $duration=0;
        $plans='';
        $cards='';
        if(Auth::check()):
            $plans=Auth::user()->transaction->where('status','1')->first();
            $total_contribution = $this->contributorService->countContributions();
            if($plans):
                $start = Carbon::parse($plans->expiry_date);
                $duration = $currentDate->diffInDays($start);
            endif;
            $cards=Auth::user()->userCards;
        endif;
        return view::make('user.user_plan.plan.active_plan')->with(['days'=>$duration, 'activePlan'=>$plans, 'cards'=>$cards, 'total_contribution' => $total_contribution]);
    }

    /**
     * @param UserCard $card
     * @return mixed
     * @throws \Exception
     */
    public function deleteCard(UserCard $card){
        $card->delete();
        $notification = array(
            'message' => trans('content.card_deleted'),
            'alert_type' => 'success'
        );
        return Redirect::to(lang_url('active-plan'))->with($notification);
    }

    /**
     * @return mixed
     */
    public function redeemAllPoints(){
        $user_data=['user_id'=>Auth::user()->id];
        $points=$this->user_points->points($user_data);
        $remainingPoints=0;
        $total=0;
        foreach($points as $user_point){
            $getRedeemPoints=Auth::user()->redeemPoints->where('type', $user_point['type'])->sum('points');
            $remainingPoints=$user_point['sum']-$getRedeemPoints;
            $total=$total+$remainingPoints;
            if($remainingPoints > 0):
                $savePoint=$this->createPoint($remainingPoints, $user_point['type']);
            endif;
        }
        $notification = array(
            'message' => trans('content.redeem_points'),
            'alert_type' => 'success'
        );
        $earning=$this->getEarning($total);
        $email_data=['first_name'=>Auth::user()->first_name, 'last_name'=>Auth::user()->last_name, 'points'=>$total, 'earning'=>$earning];
        Mail::to(Auth::user()->email)->send(new RedeemPoint($email_data));
        return Redirect::to(lang_url('redeem-points'))->with($notification);
    }

    /**
     * @param $points
     * @param $type
     * @return mixed
     */
    public function createPoint($points, $type){
        $earning=$this->getEarning($points);
        $data=['points'=>$points, 'type'=>$type, 'earning'=>$earning, 'status'=>0, 'user_id'=>Auth::user()->id];
        $services=$this->userServices->saveRedeemPoints($data);
        return $earning;
    }

    /**
     * @param $points
     * @return \___PHPSTORM_HELPERS\static|mixed
     */
    public function getEarning($points){
        $pointsPrices=PointsPrice::all();
        foreach($pointsPrices as $key2=>$range):
            if($points >=1000 && ($range['min_points']==0) && ($points >= $range['max_points'])):
                $earning=$range['price']*$points;
                break;
            elseif(($points >= $range['min_points']) && ($points <= $range['max_points'])):
                $earning=$range['price']*$points;
                break;
            endif;
        endforeach;
        return $earning;
    }
}
