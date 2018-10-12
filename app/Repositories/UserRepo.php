<?php namespace App\Repositories;

use App\User;
use App\Transaction;

class UserRepo {

    /**
     * @var string
     */
    private $class = 'App\User';
    /**
     *
     */
    private $user;

    /**
     * UserRepo constructor.
     */
    public function __construct()
    {
        $user= new User();
        $this->user = $user;
    }

    /**
     * @param User $user
     */
    public function create(User $user)
    {
        $this->user->persist($user);
        $this->user->flush();
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        return $user=$this->user->where('id', $id)->update($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return $this->user->find($id);
    }

    /**
     * @param User $user
     */
    public function delete(User $user)
    {
        $this->user->remove($user);
        $this->user->flush();
    }

    /**
     * create Post
     * @return mixed
     */
    private function perpareData($data)
    {
        return new User($data);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function updateToken($id){
        $user=$this->findById($id);
        $user->token=md5(microtime());
        $user->save();
        return $user;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function makeActive($id){
        return $this->user->where('id', $id)->update(['status'=>'1']);
    }

    /**
     * @param $coins
     * @param $user_id
     * @return mixed
     */
    public function updateCoins($coins, $user_id){
        $user=$this->findById($user_id);
        $sumCoins=['coins'=>$coins+$user->coins];
        return $this->update($user_id, $sumCoins);

    }


    /**
     * @return int
     */
    public function count(){
        return User::all()->count();
    }

    /**
     * @return int
     */
    public function countActive(){
        return User::where('status',1)->get()->count();
    }
}
?>
