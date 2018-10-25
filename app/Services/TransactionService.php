<?php

namespace App\Services;
/**
 * Copyright (c) 2018, fahad-shehzad.com All rights reserved.
 *
 * @author Muhammad Adeel
 * @since Feb 23, 2018
 * @package app.contextionary.services
 *
 */

use App\Repositories\CoinsRepo;
use App\Notifications\InvoicePaid;
use App\Repositories\UserRepo;
use Stripe\Error\Card;
use Cartalyst\Stripe\Stripe;
use App\TransactionDetail;
use Carbon;
use App\Repositories\TransactionRepo;
use Auth;

class TransactionService extends BaseService implements IService
{
    protected $role;
    protected $userServices;
    protected $transactionDetail;
    protected $transactionRepo;
    protected $contService;
    protected $coinsRepo;
    protected $userRepo;
    protected $stripe;

    /**
     * TransactionService constructor.
     */
    public function __construct(){
        $this->role             =   new RoleService();
        $this->transactionRepo  =   new TransactionRepo();
        $this->userServices     =   new UserService();
        $this->contService      =   new ContributorService();
        $this->coinsRepo        =   new CoinsRepo();
        $this->userRepo         =   new UserRepo();
        $this->stripe           = Stripe::make(env('STRIPE_SECRET'));
    }

    /**
     * @param $request
     * @return array|\Illuminate\Http\RedirectResponse
     */
    public function paymentProcess($request){
        $input = array_except($request,array('_token'));
        try {
//            $cardInfo['user_id']=$request['user_id'];
            if($request['type']=='buy_package') {
                $cus_data = $this->createOrFetchCustomer($request);
                $arr= [
                    'customer' => $cus_data['cus_id'],
                    'card' => $cus_data['card_id'],
                    'currency' => 'USD',
                    'amount' => $request['price'],
                    'description' => 'Add in wallet',
                ];
            }else{
                $token = $this->getToken($request);
                $arr= [
                    'card' => $token['id'],
                    'currency' => 'USD',
                    'amount' => $request['price'],
                    'description' => 'Add in wallet',
                ];
            }
            $charge = $this->stripe->charges()->create($arr);
            if($charge['status'] == 'succeeded') {
                /**
                 * Write Here Your Database insert logic.
                 */
                $latestCard =   [
                                    'user_id'   =>  $request['user_id'],
                                    'last4'     =>  $charge['source']['last4']
                                ];
                $getCard    =   $this->userServices->getCard($latestCard);
                if(empty($getCard)):
                    $latestCard['exp_month']    =   $charge['source']['exp_month'];
                    $latestCard['exp_year']     =   $charge['source']['exp_year'];
                    $latestCard['card_id']      =   $charge['source']['id'];
                    $latestCard['brand']        =   $charge['source']['brand'];
                    $this->userServices->insertCardInfo($latestCard);
                endif;
                $data=[
                        'transaction_id'=>  $charge['id'],
                        'user_id'       =>  $request['user_id'],
                        'package_id'    =>  $request['package_id'],
                        'type'          =>  $request['type'],
                        'amount'        =>  $request['price']
                ];
                $updateTransaction  =   $this->success($data);
                if($request['type']=='purchase_coins'){
                    $updateUser=$this->contService->updateCoins($request['user_id'], $request['package_id']);
                    return ['status'=>true];
                }else{
                    $data=['user_roles'=>$request['package_id']];
                    $this->userServices->updateRecord(Auth::user()->id, $data);
                    $notify=$this->userServices->notifyUser($request['user_id'], $updateTransaction);
                    return ['status'=>true, 'user'=>$notify];
                }

            } else {
                $notification = array(
                    'message' => trans('content.no_money'),
                    'alert_type' => 'danger'
                );
                return ['status'=>false, 'notification'=>$notification];
            }
        } catch (Exception $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert_type' => 'error'
            );
            return ['status'=>false, 'notification'=>$notification];
        } catch(\Cartalyst\Stripe\Exception\CardErrorException $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert_type' => 'error'
            );
            return ['status'=>false, 'notification'=>$notification];
        } catch(\Cartalyst\Stripe\Exception\MissingParameterException $e) {
            $notification = array(
                'message' => $e->getMessage(),
                'alert_type' => 'error'
            );
            return ['status'=>false, 'notification'=>$notification];
        }
    }

    public function createOrFetchCustomer($request){
        $user = $this->userRepo->findById($request['user_id']);
        if($user->cus_id == ''){
            $token = $this->getToken($request);
            $customer = $this->stripe->customers()->create([
                'email' => $user['email'],
                'source'=> $token['id'],
            ]);
            $token = $this->getToken($request);
            $card = $this->stripe->cards()->create($customer['id'], $token['id']);
            $data1 = [
                'cus_id' => $customer['id'],
            ];
            $data2 = [
                'cus_id' => $customer['id'],
                'card_id'=> $card['id']
            ];
            $this->userRepo->update($user->id, $data1);
            return $data2;
        }else{
            $token = $this->getToken($request);
            $card = $this->stripe->cards()->create($user->cus_id, $token['id']);
            $data1 = [
                'cus_id' => $user->cus_id
            ];
            $data2 = [
                'cus_id' => $user->cus_id,
                'card_id'=> $card['id']
            ];
            $this->userRepo->update($request['user_id'], $data1);
            return $data2;
        }
    }

    /**
     * @param $request
     * @return mixed
     */
    public function getToken($request){
        $cardInfo=[
            'number' => $request['card_no'],
            'exp_month' => $request['ccExpiryMonth'],
            'exp_year' => $request['ccExpiryYear'],
            'cvc' => $request['cvvNumber'],
        ];
        $token = $this->stripe->tokens()->create([
            'card' => $cardInfo
        ]);
        if (!isset($token['id'])) {
            return redirect()->route('addmoney.paywithstripe');
        }
        return $token;
    }

    /**
     * @param $params
     * @return mixed
     */
    public function success($params){

        $data=[
                'transaction_id'    =>  $params['transaction_id'],
                'purchase_type'     =>  $params['type'],
                'user_id'           =>  $params['user_id']
        ];
        if($params['type']=='purchase_coins'){
            $coins=$this->coinsRepo->findById($params['package_id']);
            if($coins){
                $data['coins']=$coins->coins;
            }
        }else{
            $data['package_id']     =   $params['package_id'];
            $data['expiry_date']    =   Carbon::parse()->addMonth();
            $data['status']         =   1;
            $updatePrevious         =   [
                                            'user_id'       =>  $params['user_id'],
                                            'purchase_type' =>  'buy_package'
                                        ];
            $this->transactionRepo->update($updatePrevious, ['status'=>0]);
        }
        $data['amount']     =   $params['amount'];
        $new_transaction    =   $this->transactionRepo->create($data);
        if($params['type']  ==  'buy_package'):
            $assignRoleToUser   =   $this->role->assign($params['user_id'], $params['package_id']);
        endif;
        return $new_transaction;
    }

    public function getTransaction($user_id){
        return $this->transactionRepo->getRecord($user_id);
    }
}
