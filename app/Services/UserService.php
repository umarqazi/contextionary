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
        $this->user_repo        =   new UserRepo();
        $this->transactionRepo  =    new TransactionRepo();
        $this->coin             =   new CoinsRepo();
    }

    /**
     * @return int
     */
    public function count(){
        return $this->user_repo->count();
    }

    /**
     * @return int
     */
    public function countActive(){
        return $this->user_repo->countActive();
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
     * @param $id
     * @return mixed
     */
    public function get($id){
        return $this->user_repo->findById($id);
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function updateRecord($id, $data){
        return $this->user_repo->update($id, $data);
    }
}
