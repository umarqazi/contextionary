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

    public function __construct()
    {
        $user= new User();
        $this->user = $user;
    }

    public function create(User $user)
    {
        $this->user->persist($user);
        $this->user->flush();
    }

    public function update($id, $data)
    {
        return $user=$this->user->where('id', $id)->update($data);
    }

    public function findById($id)
    {
        return $this->user->find($id);
    }

    public function delete(User $user)
    {
        $this->user->remove($user);
        $this->user->flush();
    }

    /**
     * create Post
     * @return Post
     */
    private function perpareData($data)
    {
        return new User($data);
    }
    public function updateToken($id){
        $user=$this->findById($id);
        $user->token=md5(microtime());
        $user->save();
        return $user;
    }
    public function makeActive($id){
        return $this->user->where('id', $id)->update(['status'=>'1']);
    }
    /**
     * update coins
     */
    public function updateCoins($coins, $user_id){
        $user=$this->findById($user_id);
        $sumCoins=['coins'=>$coins+$user->coins];
        return $this->update($user_id, $sumCoins);

    }

    /**
     * @return mixed
     */
    public function getListingForAuthUser($user)
    {
        return $user->glossary()->paginate();
    }

    /**
     * @return mixed
     */
    public function addToFav($user, $glossary_item_id)
    {
        return $user->glossary()->attach($glossary_item_id);
    }

    /**
     * @return mixed
     */
    public function removeFromFav($user, $glossary_item_id)
    {
        return $user->glossary()->detach($glossary_item_id);
    }
}
?>
