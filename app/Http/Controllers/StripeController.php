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
            'card_no' => strip_tags($stripe->card_no),
            'ccExpiryMonth' => strip_tags($stripe->ccExpiryMonth),
            'ccExpiryYear' => strip_tags($stripe->ccExpiryYear),
            'cvvNumber' => strip_tags($stripe->cvvNumber),
            'user_id'=>$stripe->user_id,
            'price'=>strip_tags($stripe->price),
            'package_id'=>strip_tags($stripe->package_id),
            'type'=>strip_tags($stripe->type),
            'auto'=>strip_tags($stripe->auto),
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
    public function postAutoPaymentWithStripe(Request $request){
        $payment    =   $this->stripe->autoPaymentProcess($request->id);
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
