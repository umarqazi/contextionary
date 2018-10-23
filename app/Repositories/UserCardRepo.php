<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 10/16/18
 * Time: 3:11 PM
 */

namespace App\Repositories;


use App\UserCard;

class UserCardRepo
{
    protected $card;

    /**
     * UserCardRepo constructor.
     */
    public function __construct()
    {
        $this->card=new UserCard();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function create($data){
        return $this->card->create($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getCard($data)
    {
        return $this->card->where($data)->first();
    }

    /**
     * @param $data
     * @return mixed
     */
    public function getAllCard($data)
    {
        return $this->card->where($data)->get();
    }
}