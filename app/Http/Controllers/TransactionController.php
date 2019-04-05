<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 4/2/19
 * Time: 1:07 PM
 */

namespace App\Http\Controllers;


use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TransactionController
{
    public $stripe;

    /**
     * TransactionController constructor.
     */
    public function __construct(){
        $this->paypalService=new TransactionService();
    }

    public function paypal(Request $request){
        $url = $this->paypalService->payWithPaypal($request->all());
        return redirect($url );
    }

    public function getCredientials(Request $request){
        if($request->has('token')){
            $response = $this->paypalService->getCheckoutDetail($request->token);
            if($response==true){
                $notification = array(
                    'message' => 'Coins has been added into your account',
                    'alert_type' => 'success'
                );
                return Redirect::to(lang_url('coins-list'))->with($notification);
            }else{
                $notification = array(
                    'message' => 'Something went wrong',
                    'alert_type' => 'error'
                );
                return Redirect::to(lang_url('coins-list'))->with($notification);
            }
        }else{
            $notification = array(
                'message' => 'Invalid Token',
                'alert_type' => 'error'
            );
            return Redirect::to(lang_url('coins-list'))->with($notification);
        }
    }

    public function cancelPaypalRequest(){
        session()->put('package_id', '');
        $notification = array(
            'message' => 'Your request has been cancel',
            'alert_type' => 'error'
        );
        return Redirect::to(lang_url('coins-list'))->with($notification);
    }
}