<?php

namespace App\Services;
/**
 * Copyright (c) 2018, fahad-shehzad.com All rights reserved.
 *
 * @author Muhammad Adeel
 * @since Feb 23, 2018
 * @package app.contextionary.services
 * @project starzplay
 *
 */

use App\Repositories\CoinsRepo;
use App\User;
use App\Notifications\InvoicePaid;
use App\Transaction;
use Stripe\Error\Card;
use Cartalyst\Stripe\Stripe;
use App\Http\Controllers\UsersController;
use App\TransactionDetail;
use Carbon;
use App\Repositories\TransactionRepo;
use Auth;

class TransactionService
{
    protected $role;
    protected $userServices;
    protected $transactionDetail;
    protected $transactionRepo;
    protected $contService;
    protected $coinsRepo;

    /**
     * TransactionService constructor.
     */
    public function __construct(){
        $this->role             =   new RoleService();
        $this->transactionRepo  =   new TransactionRepo();
        $this->userServices     =   new UserService();
        $this->contService      =   new ContributorService();
        $this->coinsRepo        =   new CoinsRepo();
    }

    /**
     * @param $request
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function paymentProcess($request){
        $input = array_except($request,array('_token'));
        $stripe = Stripe::make(env('STRIPE_SECRET'));
        try {
            $cardInfo=[
                'number' => $request['card_no'],
                'exp_month' => $request['ccExpiryMonth'],
                'exp_year' => $request['ccExpiryYear'],
                'cvc' => $request['cvvNumber'],
            ];
            $token = $stripe->tokens()->create([
                'card' => $cardInfo
            ]);
            $cardInfo['user_id']=$request['user_id'];
            if (!isset($token['id'])) {
                return redirect()->route('addmoney.paywithstripe');
            }
            $charge = $stripe->charges()->create([
                'card' => $token['id'],
                'currency' => 'USD',
                'amount' => $request['price'],
                'description' => 'Add in wallet',
            ]);
            if($charge['status'] == 'succeeded') {
                /**
                 * Write Here Your Database insert logic.
                 */
                $data=['transaction_id'=>$charge['id'], 'user_id'=>$request['user_id'], 'package_id'=>$request['package_id'], 'type'=>$request['type'],'amount' => $request['price']];
                $updateTransaction=$this->success($data);
                if($request['type']=='purchase_coins'){
                    $updateUser=$this->contService->updateCoins($request['user_id'], $request['package_id']);
                    return ['status'=>true];
                }else{
                    $data=['user_roles'=>$request['package_id']];
                    $this->userServices->updateRecord(Auth::user()->id, $data);
                    $notify=$this->userServices->notifyUser($request['user_id'], $updateTransaction);
                    return ['status'=>true, 'user'=>$notify];
                }

            } else {
                $notification = array(
                    'message' => trans('content.no_money'),
                    'alert_type' => 'danger'
                );
                return ['status'=>false, 'notification'=>$notification];
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
     * @param $params
     * @return mixed
     */
    public function success($params){

        $data=['transaction_id'=>$params['transaction_id'], 'purchase_type'=>$params['type'],'user_id'=>$params['user_id']];
        if($params['type']=='purchase_coins'){
            $coins=$this->coinsRepo->findById($params['package_id']);
            if($coins){
                $data['coins']=$coins->coins;
            }
        }else{
            $data['package_id']=$params['package_id'];
            $data['expiry_date']=Carbon::parse()->addMonth();
        }
        $data['amount']=$params['amount'];
        $new_transaction=$this->transactionRepo->create($data);
        if($params['type']=='buy_package'):
            $assignRoleToUser=$this->role->assign($params['user_id'], $params['package_id']);
        endif;
        return $new_transaction;
    }

    public function getTransaction($user_id){
        return $this->transactionRepo->getRecord($user_id);
    }
}
