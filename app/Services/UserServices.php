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
use App\Repositories\UserRepo;

class UserServices
{
    /**
     * @return int
     */
     protected $user;

     public function __construct(UserRepo $user)
     {
         $this->user = $user;
     }
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

    public function pTransaction($transaction, $user_id, $package_id){
      $data=['transaction_id'=>$transaction['id'], 'user_id'=>$user_id, 'package_id'=>$package_id];
      $new_transaction=$this->user->pTransaction($data);
      $updateUser=$this->user->PostOfId($user_id);
      $updateUser->notify(new InvoicePaid($new_transaction));
      $assignRoleToUser=self::assignUserRole($user_id, $package_id);
      return $updateUser;
    }

    public function authenticateToken($id, $token){
      $user=$this->user->PostOfId($id);
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

    public function updateToken($id){
      $user=User::find($id);
      $user->token=md5(microtime());
      $user->save();
      return $user->token;
    }
    public function assignUserRole($user_id, $package_id){
        $package_name=strtolower(Config::get('constant.packages.'.$package_id));
        $user=$this->user->PostOfId($user_id);
        $assignRole=$user->assignRole($package_name);
        return true;
    }


}
