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
        $payment=$this->stripe->paymentProcess($stripe);
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
