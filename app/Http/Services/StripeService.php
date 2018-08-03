<?php

namespace App\Http\Services;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\InvoicePaid;
use App\TransactionHistory;
use Carbon;
use Stripe\Error\Card;
use Cartalyst\Stripe\Stripe;
use App\Http\Controllers\UsersController;

class StripeServices
{
  public static function paymentProcess($request){
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
        $updateTransaction=UsersController::updateTransaction($charge, $request->user_id, $request->package_id);
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
