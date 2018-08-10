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
use App\TransactionHistory;
use Stripe\Error\Card;
use Cartalyst\Stripe\Stripe;
use App\Http\Controllers\UsersController;

class StripeServices
{
    protected $userController;
    protected $userServices;
    public function __construct(UsersController $controller, UserServices $services){
        $this->userController= $controller;
        $this->userServices= $services;
    }
    public function paymentProcess($request){
        $input=$request->all();
        $input = array_except($input,array('_token'));
        $stripe = Stripe::make(env('STRIPE_SECRET'));
        try {
            $token = $stripe->tokens()->create([
                'card' => [
                    'number' => $request->get('card_no'),
                    'exp_month' => $request->get('ccExpiryMonth'),
                    'exp_year' => $request->get('ccExpiryYear'),
                    'cvc' => $request->get('cvvNumber'),
                ],
            ]);
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
                $updateTransaction=$this->userServices->pTransaction($charge, $request->user_id, $request->package_id);
                return ['status'=>true, 'user'=>$updateTransaction];

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
}
