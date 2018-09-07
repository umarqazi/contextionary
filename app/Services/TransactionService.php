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

use App\User;
use App\Notifications\InvoicePaid;
use App\Transaction;
use Stripe\Error\Card;
use Cartalyst\Stripe\Stripe;
use App\Http\Controllers\UsersController;
use App\TransactionDetail;
use Carbon;
use App\Repositories\TransactionRepo;

class TransactionService
{
    protected $role;
    protected $userServices;
    protected $transactionDetail;
    protected $transactionRepo;
    protected $contService;

    public function __construct(RoleService $roles,TransactionRepo $transaction_repo, UserService $services, ContributorService $contributorService){
        $this->userServices= $services;
        $this->transactionRepo= $transaction_repo;
        $this->role=$roles;
        $this->contService=$contributorService;
    }
    public function paymentProcess($request){
        $input = array_except($request,array('_token'));
        $stripe = Stripe::make('sk_test_v1HvFX0FFpIikIB48jsUwETa');
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
                $data=['transaction_id'=>$charge['id'], 'user_id'=>$request['user_id'], 'package_id'=>$request['package_id'], 'type'=>$request['type']];
                $updateTransaction=$this->success($data);
                if($request['type']=='purchase_coins'){
                    $updateUser=$this->contService->updateCoins($request['user_id'], $request['package_id']);
                    return ['status'=>true];
                }else{
                    $notify=$this->userServices->notifyUser($request['user_id'], $updateTransaction);
                    return ['status'=>true, 'user'=>$notify];
                }

            } else {
                $notification = array(
                    'message' => 'Money not add in wallet!!',
                    'alert_type' => 'danger'
                );
                return ['status'=>false, 'notification'=>$notification];
            }
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert_type' => 'danger'
            );
            return ['status'=>false, 'notification'=>$notification];
        } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert_type' => 'danger'
            );
            return ['status'=>false, 'notification'=>$notification];
        } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert_type' => 'danger'
            );
            return ['status'=>false, 'notification'=>$notification];
        }
    }
    public function success($params){
        $data=['transaction_id'=>$params['transaction_id'], 'purchase_type'=>$params['type'],'user_id'=>$params['user_id'], ($params['type']=='purchase_coins')?'coin_id':'package_id'=>$params['package_id'], 'expiry_date'=>Carbon::parse()->addMonth()];
        $new_transaction=$this->transactionRepo->create($data);
        if($params['type']=='buy_package'):
            $assignRoleToUser=$this->role->assign($params['user_id'], $params['package_id']);
        endif;
        return $new_transaction;
    }
}
