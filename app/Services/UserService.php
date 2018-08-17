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

class UserService
{
    /**
     * @return int
     */
    protected $user;
    protected  $transactionRepo;

    public function __construct(UserRepo $user, TransactionRepo $transaction_repo)
    {
        $this->user = $user;
        $this->transactionRepo=$transaction_repo;
    }
    public function count(){
        return User::all()->count();
    }

    public function roles(User $user)
    {
        return $user->roles->pluck('name');
    }

    public function profile(User $user)
    {
        return $user->userProfile();
    }

    public function delete(User $user)
    {
        return $user->delete();
    }
    public function notifyUser($id, $transaction_detail){
        $user=$this->user->findById($id);
        $user->notify(new InvoicePaid($transaction_detail));
        $this->updateStatus($id);
        return $user;
    }
    public function updateStatus($id){
         return $this->user->makeActive($id);
    }
}
