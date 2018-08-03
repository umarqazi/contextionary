<?php

namespace App\Http\Services;

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
    public function countUsers(){
        return User::all()->count();
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
}
