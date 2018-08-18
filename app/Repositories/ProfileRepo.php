<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/18/18
 * Time: 1:04 PM
 */

namespace App\Repositories;


use App\Profile;

class ProfileRepo
{
    protected $profile;
    public function __construct(Profile $profile)
    {
        $this->profile = $profile;
    }

    public function create(User $user)
    {
        $this->user->persist($user);
        $this->user->flush();
    }
    public function findById($id){
        return $this->user->where('user_id', $id)->first();
    }
    public function update($id, $data)
    {
        return $this->profile->where('user_id', $id)gi ->update($data);
    }
}