<?php

namespace App\Services;
/**
 * Copyright (c) 2018, fahad-shehzad.com All rights reserved.
 *
 * @author Muhammad Adeel
 * @since Feb 23, 2018
 * @package app.contextionary.services
 *
 */

use App\Repositories\CoinsRepo;
use App\Notifications\InvoicePaid;
use App\Repositories\PlanRepo;
use App\Repositories\UserRepo;
use Illuminate\Support\Facades\Auth;
use Stripe\Error\Card;
use Cartalyst\Stripe\Stripe;
use App\TransactionDetail;
use Carbon;
use App\Repositories\TransactionRepo;

class TransactionService extends BaseService implements IService
{
    protected $role;
    protected $userServices;
    protected $transactionDetail;
    protected $transactionRepo;
    protected $contService;
    protected $coinsRepo;
    protected $userRepo;
    protected $stripe;
    protected $plan_service;

    /**
     * TransactionService constructor.
     */
    public function __construct(){
        $this->role             =   new RoleService();
        $this->transactionRepo  =   new TransactionRepo();
        $this->userServices     =   new UserService();
        $this->contService      =   new ContributorService();
        $this->coinsRepo        =   new CoinsRepo();
        $this->userRepo         =   new UserRepo();
        $this->stripe           =   Stripe::make(env('STRIPE_SECRET'));
        $this->plan_service     =   new PlanService();
    }

    /**
     * @param $request
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function paymentProcess($request){
        $input = array_except($request,array('_token'));
        try {
            if($request['type']=='buy_package') {
                if($request['auto']=='on'){
                    $user       =   $this->userRepo->findById($request['user_id']);
                    $token      =   $this->getToken($request);
                    $cus_data   =   $this->createOrFetchCustomer($user, $token['id']);
                    $token      =   $this->getToken($request);
                    $card_data  =   $this->createCard($cus_data['cus_id'], $token['id']);
                    $plan       =   $this->plan_service->get($request['package_id']);
                    return $this->createSubscription($cus_data['cus_id'], $plan->plan_id, $request);
                }else{
                    $user       =   $this->userRepo->findById($request['user_id']);
                    $token      =   $this->getToken($request);
                    $cus_data   =   $this->createOrFetchCustomer($user, $token['id']);
                    $token      =   $this->getToken($request);
                    $card_data  =   $this->createCard($cus_data['cus_id'], $token['id']);
                    $arr= [
                        'customer'      => $cus_data['cus_id'],
                        'card'          => $card_data['id'],
                        'currency'      => 'USD',
                        'amount'        => $request['price'],
                        'description'   => 'Add in wallet',
                    ];
                    return $this->createCharge($arr, $request);
                }
            }else{
                $token = $this->getToken($request);
                $arr= [
                    'card' => $token['id'],
                    'currency' => 'USD',
                    'amount' => $request['price'],
                    'description' => 'Add in wallet',
                ];
                return $this->createCharge($arr, $request);
            }
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert_type' => 'error'
            );
            return ['status'=>false, 'notification'=>$notification];
        } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert_type' => 'error'
            );
            return ['status'=>false, 'notification'=>$notification];
        } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert_type' => 'error'
            );
            return ['status'=>false, 'notification'=>$notification];
        }
    }

    /**
     * @param $package_id
     * @return array
     */

    public function autoPaymentProcess($package_id){
        $user       =   $this->userRepo->findById(Auth::user()->id);
        $plan       =   $this->plan_service->get($package_id);
        $data=[
            'user_id'           =>  Auth::user()->id,
            'package_id'        =>  $package_id,
            'type'              =>  'buy_package',
            'price'             =>  $plan['amount'],
            'auto'              =>  'on',
            'transition'        =>  1,
        ];
        $sub = $this->createSubscription($user->cus_id, $plan->plan_id, $data);
        return $sub;
    }

    /**
     * @return mixed
     */
    public function cancelAutoPaymentProcess(){
        $user       =   $this->userRepo->findById(Auth::user()->id);
        $transaction = $this->transactionRepo->getRecord([
            'user_id' => $user->id,
            'status' => 1,
        ]);
        $sub = $this->cancelSubscription($user->cus_id, $transaction[0]->transaction_id);
        if($sub['cancel_at_period_end'] == 1){
            $this->transactionRepo->update(['user_id' => Auth::user()->id], ['auto' => 0]);
        }
        return $sub;
    }

    /**
     * @param $cus_id
     * @param $sub_id
     * @return mixed
     */
    public function cancelSubscription($cus_id, $sub_id){
        return $subscription = $this->stripe->subscriptions()->cancel($cus_id, $sub_id, true);
    }

    /**
     * @param $arr
     * @param $request
     * @return array
     */
    public function createCharge($arr, $request){
        $charge = $this->stripe->charges()->create($arr);
        if($charge['status'] == 'succeeded') {
            /**
             * Write Here Your Database insert logic.
             */
            $latestCard =   [
                'user_id'   =>  $request['user_id'],
                'last4'     =>  $charge['source']['last4']
            ];
            $getCard    =   $this->userServices->getCard($latestCard);
            if(empty($getCard)):
                $latestCard['exp_month']    =   $charge['source']['exp_month'];
                $latestCard['exp_year']     =   $charge['source']['exp_year'];
                $latestCard['card_id']      =   $charge['source']['id'];
                $latestCard['brand']        =   $charge['source']['brand'];
                $this->userServices->insertCardInfo($latestCard);
            endif;
            $data=[
                'transaction_id'    =>  $charge['id'],
                'user_id'           =>  $request['user_id'],
                'package_id'        =>  $request['package_id'],
                'type'              =>  $request['type'],
                'amount'            =>  $request['price'],
                'auto'              =>  'off'
            ];
            $updateTransaction  =   $this->success($data);
            if($request['type']=='purchase_coins'){
                $updateUser=$this->contService->updateCoins($request['user_id'], $request['package_id']);
                return ['status'=>true];
            }else{
                $data   =   ['user_roles'=>$request['package_id']];
                $this->userServices->updateRecord(Auth::user()->id, $data);
                $notify =   $this->userServices->notifyUser($request['user_id'], $updateTransaction);
                return ['status'=>true, 'user'=>$notify];
            }

        }
        else {
            $notification = array(
                'message' => trans('content.no_money'),
                'alert_type' => 'danger'
            );
            return ['status'=>false, 'notification'=>$notification];
        }
    }

    /**
     * @param $cus
     * @param $plan
     * @param $request
     * @return array
     */
    public function createSubscription($cus, $plan, $request){
        if($request['transition'] == '1'){
             $transaction = $this->transactionRepo->getRecord([
                'user_id' => $request['user_id'],
                'status' => 1,
                ]);
            $subscription   = $this->stripe->subscriptions()->create( $cus,
                    [
                        'plan'      =>  $plan,
                        'trial_end' =>  strtotime($transaction[0]->expiry_date),
                    ]
                );
        }else{
            $subscription = $this->stripe->subscriptions()->create($cus, ['plan' => $plan]);
        }
        if($subscription['status'] == 'active' || $subscription['status'] == 'trialing') {
            $data=[
                'transaction_id'    =>  $subscription['id'],
                'user_id'           =>  $request['user_id'],
                'package_id'        =>  $request['package_id'],
                'type'              =>  $request['type'],
                'amount'            =>  $request['price'],
                'auto'              =>  $request['auto'],
                'expiry_date'       =>  date('Y-m-d H:i:s', $subscription['current_period_end']),
            ];
            $updateTransaction      =   $this->success($data);
            $data                   =   ['user_roles'=>$request['package_id']];
            $this->userServices->updateRecord(Auth::user()->id, $data);
            $notify                 =   $this->userServices->notifyUser($request['user_id'], $updateTransaction);
            return ['status'=>true, 'user'=>$notify];
        }
        else {
            $notification = array(
                'message' => trans('content.no_money'),
                'alert_type' => 'danger'
            );
            return ['status'=>false, 'notification'=>$notification];
        }
    }

    /**
     * @param $user
     * @param $token_id
     * @return array
     */
    public function createOrFetchCustomer($user, $token_id){
        if($user->cus_id == ''){
            $customer = $this->stripe->customers()->create([
                'email' => $user->email,
                'source'=> $token_id,
            ]);
            $data1 = [
                'cus_id' => $customer['id'],
            ];
            $this->userRepo->update($user->id, $data1);
            return $data1;
        }
        else{
            $data1 = [
                'cus_id' => $user->cus_id
            ];
            $this->userRepo->update($user->cus_id, $data1);
            return $data1;
        }
    }

    /**
     * @param $cus_id
     * @param $token_id
     * @return mixed
     */
    public function createCard($cus_id, $token_id){
        return $card = $this->stripe->cards()->create($cus_id, $token_id);
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getToken($request){
        $cardInfo=[
            'number' => $request['card_no'],
            'exp_month' => $request['ccExpiryMonth'],
            'exp_year' => $request['ccExpiryYear'],
            'cvc' => $request['cvvNumber'],
        ];
        $token = $this->stripe->tokens()->create([
            'card' => $cardInfo
        ]);
        if (!isset($token['id'])) {
            return redirect()->route('addmoney.paywithstripe');
        }
        return $token;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function success($params){

        $data=[
                'transaction_id'    =>  $params['transaction_id'],
                'purchase_type'     =>  $params['type'],
                'user_id'           =>  $params['user_id']
        ];
        if($params['type']=='purchase_coins'){
            $coins=$this->coinsRepo->findById($params['package_id']);
            if($coins){
                $data['coins']          =   $coins->coins;
                $data['auto']           =   0;
            }
        }else{
            if($params['auto']=='on'){
                $data['sub']            =   1;
                $data['expiry_date']    =   $params['expiry_date'];
                $data['auto']           =   1;
            }
            else {
                $data['sub']            =   0;
                $data['expiry_date']    =   Carbon::parse()->addMonth();
                $data['auto']           =   0;
            }
            $data['status'] = 1;
            $data['package_id'] = $params['package_id'];
            $updatePrevious = [
                'user_id' => $params['user_id'],
                'purchase_type' => 'buy_package'
            ];
            $this->transactionRepo->update($updatePrevious, ['status' => 0, 'auto' => 0]);
        }
        $data['amount']     =   $params['amount'];
        $new_transaction    =   $this->transactionRepo->create($data);
        if($params['type']  ==  'buy_package'):
            $assignRoleToUser   =   $this->role->assign($params['user_id'], $params['package_id']);
        endif;
        return $new_transaction;
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function getTransaction($user_id){
        return $this->transactionRepo->getRecord($user_id);
    }
}
