<?php
namespace App\Http\Controllers;
use App\Http\Requests;
use Illuminate\Http\Request;
use Validator;
use URL;
use Session;
use Redirect;
use Input;
use App\User;
use Stripe\Error\Card;
use Cartalyst\Stripe\Stripe;
use Auth;
use App\Services\StripeServices;

class StripeController extends Controller
{
  public function postPaymentWithStripe(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'card_no' => 'required',
      'ccExpiryMonth' => 'required',
      'ccExpiryYear' => 'required',
      'cvvNumber' => 'required',
    ]);
    $input = $request->all();
    if ($validator->passes()) {
      $payment=StripeServices::paymentProcess($request);
      if($payment['status']==true){
        if($payment['user']){
          $user=Auth::login($payment['user']);
          if(Auth::check()){
            return Redirect::to('/dashboard');
          }
        }
      }else{
        return Redirect::back()->with($payment['notification']);
      }
    }
  }
}
