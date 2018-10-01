<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/10/18
 * Time: 4:53 PM
 */

namespace App\Services;

use App\Repositories\UserRepo;

class AuthService
{
    public function __construct()
    {
        $user=new UserRepo();
        $this->user = $user;
    }

    /**
     * @param $id
     * @param $token
     * @return bool
     */
    public function authenticateToken($id, $token){
        $user=$this->user->findById($id);
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

    /**
     * @param $id
     * @return mixed
     */
    public function updateToken($id){
        $user=$this->user->updateToken($id);
        return $user->token;
    }
}