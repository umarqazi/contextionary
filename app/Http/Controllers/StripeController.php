<?php
namespace App\Http\Controllers;
use App\Http\Requests\StripePayment;
use Illuminate\Http\Request;
use Redirect;
use App\User;
use Stripe\Error\Card;
use Cartalyst\Stripe\Stripe;
use Auth;
use App\Services\TransactionService;

class StripeController extends Controller
{
    public $stripe;
    public function __construct(TransactionService $stripe){
        $this->stripe=$stripe;
    }
    public function postPaymentWithStripe(StripePayment $stripe)
    {
        $validator = $stripe->validated();
        $cardInfo=[
            'card_no' => $stripe->card_no,
            'ccExpiryMonth' => $stripe->ccExpiryMonth,
            'ccExpiryYear' => $stripe->ccExpiryYear,
            'cvvNumber' => $stripe->cvvNumber,
            'user_id'=>$stripe->user_id,
            'price'=>$stripe->price,
            'package_id'=>$stripe->package_id,
            'type'=>$stripe->type,
        ];
        $payment=$this->stripe->paymentProcess($cardInfo);
        if($payment['status']==true){
            if($stripe->type=='purchase_coins'){
                return Redirect::to(lang_route('coins'))->with(['alert_type'=>'success', 'message'=>'Coins has been added in your profile']);
            }else{
                if($payment['user']){
                    return Redirect::to('/dashboard');
                }
            }
        }else{
            return Redirect::back()->with($payment['notification']);
        }
    }
}
