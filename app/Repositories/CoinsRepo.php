<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/29/18
 * Time: 11:34 AM
 */

namespace App\Repositories;
use App\Coin;

class CoinsRepo
{

    /**
     * @var string
     */
    private $class = 'App\Coin';
    /**
     *
     */
    private $coin;

    public function __construct(Coin $coin)
    {
        $this->coin = $coin;
    }

    public function create(Coin $coin)
    {
        $this->coin->persist($coin);
        $this->coin->flush();
    }

    public function update($id, $data)
    {
        return $user=$this->coin->where('id', $id)->update($data);
    }

    public function findById($id)
    {
        return $this->coin->find($id);
    }
}