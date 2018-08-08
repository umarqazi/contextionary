<?php
namespace App\Services;

/**
 * Copyright (c) 2018, fahad-shehzad.com All rights reserved.
 *
 * @author Fahad Shehzad
 * @since Feb 23, 2018
 * @package app.contextionary.services
 * @project starzplay
 *
 */
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\InvoicePaid;
use App\TransactionHistory;
use Carbon;
use Config;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserServices
{
    /**
     * @return int
     */
    public function countUsers(){
        return User::all()->count();
    }

    public function roles(User $user)
    {
        return $user->roles->pluck('name');
    }

    public function delete(User $user)
    {
        return $user->delete();
    }

    public static function pTransaction($transaction, $user_id, $package_id){
      $new_transaction=new TransactionHistory;
      $new_transaction->transaction_id=$transaction['id'];
      $new_transaction->user_id=$user_id;
      $new_transaction->package_id=$package_id;
      $new_transaction->save();
      $updateUser=User::find($user_id);
      $updateUser->status=1;
      $updateUser->expiry_date=strtotime(Carbon::now()->addMonth());
      $updateUser->save();
      $updateUser->notify(new InvoicePaid($new_transaction));
      return $updateUser;
    }

    public static function authenticateToken($id, $token){
      $user=User::find($id);
      if($user){
        if($user->token==$token){
          return true;
        }else{
          return false;
        }
      }else{
        return false;
      }
    }

    public static function updateToken($id){
      $user=User::find($id);
      $user->token=md5(microtime());
      $user->save();
      return $user->token;
    }

    public static function assignRole($user_id, $package_id){
      $package_name=strtolower(Config::get('constant.packages.'.$package_id));
      $getRoleName=Role::where('name',$package_name)->first();
      $user=User::find($user_id);
      $assignRole=$user->assignRole($package_name);
      return true;
    }

    /**
     *
     */
    public function persist($params)
    {
        // TODO: Implement persist() method.
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        // TODO: Implement findById() method.
    }

    /**
     * @param $params
     * @return mixed
     */
    public function update($params)
    {
        // TODO: Implement update() method.
    }

    public function remove($id)
    {
        // TODO: Implement remove() method.
    }

    /**
     * Paginated data based on params.
     * @param $params
     * @return mixed
     */
    public function search($params)
    {
        // TODO: Implement search() method.
    }


}
