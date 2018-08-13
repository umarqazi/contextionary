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

    public function __construct(RoleService $roles,TransactionRepo $transaction_repo, UserService $services){
        $this->userServices= $services;
        $this->transactionRepo= $transaction_repo;
        $this->role=$roles;
    }
    public function paymentProcess($request){
        $input=$request->all();
        $input = array_except($input,array('_token'));
        $stripe = Stripe::make(env('STRIPE_SECRET'));
        try {
            $cardInfo=[
                'number' => $request->get('card_no'),
                'exp_month' => $request->get('ccExpiryMonth'),
                'exp_year' => $request->get('ccExpiryYear'),
                'cvc' => $request->get('cvvNumber'),
            ];
            $token = $stripe->tokens()->create([
                'card' => $cardInfo
            ]);
            $cardInfo['user_id']=$request->user_id;
            if (!isset($token['id'])) {
                return redirect()->route('addmoney.paywithstripe');
            }
            $charge = $stripe->charges()->create([
                'card' => $token['id'],
                'currency' => 'USD',
                'amount' => $request->price,
                'description' => 'Add in wallet',
            ]);

            if($charge['status'] == 'succeeded') {
                /**
                 * Write Here Your Database insert logic.
                 */
                $updateTransaction=$this->success($charge, $request->user_id, $request->package_id);
                $notify=$this->userServices->notifyUser($request->user_id, $updateTransaction);
                return ['status'=>true, 'user'=>$notify];

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
    public function success($transaction, $user_id, $package_id){
        $data=['transaction_id'=>$transaction['id'], 'user_id'=>$user_id, 'package_id'=>$package_id, 'expiry_date'=>Carbon::parse()->addMonth()];
        $new_transaction=$this->transactionRepo->create($data);
        $assignRoleToUser=$this->role->assign($user_id, $package_id);
        return $new_transaction;
    }
}
