<?php namespace App\Repositories;

use App\User;
use App\TransactionHistory;

class UserRepo {

  /**
  * @var string
  */
  private $class = 'App\User';
  /**
  *
  */
  private $user;

  public function __construct(User $user)
  {
    $this->user = $user;
  }

  public function create(User $user)
  {
    $this->user->persist($user);
    $this->user->flush();
  }

  public function update(User $user, $data)
  {
    $user->setTitle($data['title']);
    $user->setBody($data['body']);
    $this->user->persist($user);
    $this->user->flush();
  }

  public function PostOfId($id)
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
  public function pTransaction($data){

    return TransactionHistory::create($data);
  }
}
?>
