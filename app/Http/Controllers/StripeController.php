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
use Illuminate\Support\Facades\Input;

class StripeController extends Controller
{
    public $stripe;

    /**
     * StripeController constructor.
     */
    public function __construct(){
        $this->stripe=new TransactionService();
    }

    /**
     * @param StripePayment $stripe
     * @return mixed
     */
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
            'auto'=>$stripe->auto,
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
            return Redirect::back()->with($payment['notification'])->withInput(Input::all());
        }
    }

    /**
     * @return mixed
     */
    public function postAutoPaymentWithStripe($plan){
        $payment    =   $this->stripe->autoPaymentProcess($plan);
        if($payment['status']==true){
            if($payment['user']){
                return 1;
            }
        }else{
            return 2;
        }
    }

    /**
     * @return int
     */
    public function cancelAutoPayment(){
        $sub    =   $this->stripe->cancelAutoPaymentProcess();
        if($sub['cancel_at_period_end'] == 1){
            return 1;
        }else{
            return 2;
        }
    }
}
