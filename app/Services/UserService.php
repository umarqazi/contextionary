<?php
namespace App\Services;

/**
 *
 * @author Muhammad Adeel
 * @since Feb 23, 2018
 * @package contextionary
 * @project Contextionary
 *
 */

use App\Repositories\CoinsRepo;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\InvoicePaid;
use App\Transaction;
use Carbon;
use Config;
use App\Repositories\UserRepo;
use App\Services\RoleService;
use App\Repositories\TransactionRepo;
use App\Jobs\SendVerificationEmail;

class UserService extends BaseService implements IService
{
    /**
     * @var UserRepo
     */
    protected $user_repo;

    /**
     * @var TransactionRepo
     */
    protected $transactionRepo;

    /**
     * @var CoinsRepo
     */
    protected $coin;

    /**
     * UserService constructor.
     */
    public function __construct()
    {
        $user_repo= new UserRepo();
        $transaction_repo= new TransactionRepo();
        $coin= new CoinsRepo();
        $this->user_repo = $user_repo;
        $this->transactionRepo=$transaction_repo;
        $this->coin=$coin;
    }

    /**
     * @return int
     */
    public function count(){
        return User::all()->count();
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function roles(User $user)
    {
        return $user->roles->pluck('name');
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function profile(User $user)
    {
        return $user->userProfile();
    }

    /**
     * @param User $user
     * @return bool|null
     * @throws \Exception
     */
    public function delete(User $user)
    {
        return $user->delete();
    }

    /**
     * @param $id
     * @param $transaction_detail
     * @return mixed
     */
    public function notifyUser($id, $transaction_detail){
        $user   =   $this->user_repo->findById($id);
        $user->notify(new InvoicePaid($transaction_detail));
        $this->updateStatus($id);
        return $user;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function updateStatus($id){
         return $this->user_repo->makeActive($id);
    }

    /**
     * @param $id
     * @return \Illuminate\Foundation\Bus\PendingDispatch
     */
    public function verificationEmail($id){
        $user   =   $this->user_repo->findById($id);
        return SendVerificationEmail::dispatch($user);
    }

    /**
     * @return mixed
     */
    public function getListingForAuthUser($user){
        return $this->user_repo->getListingForAuthUser($user);
    }

    /**
     * @return mixed
     */
    public function addToFav($user, $glossary_item_id){
        return $this->user_repo->addToFav($user, $glossary_item_id);
    }

    /**
     * @return mixed
     */
    public function removeFromFav($user, $glossary_item_id){
        return $this->user_repo->removeFromFav($user, $glossary_item_id);
    }
}
