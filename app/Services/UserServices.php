<?php

namespace App\Services;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserServices
{
    public function countUsers(){
        return User::all()->count();
    }

    public function roles(User $user){
        return $user->roles->pluck('name');
    }
}
