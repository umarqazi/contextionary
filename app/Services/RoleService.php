<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 09/08/18
 * Time: 15:49
 */

namespace App\Services;


use App\Repositories\UserRepo;
use Illuminate\Support\Facades\Config;
use App\Repositories\RoleRepo;
use Spatie\Permission\Models\Role;

class RoleService extends BaseService implements IRoleService
{
    protected $roles;
    protected $user;
    public function __construct(UserRepo $user, RoleRepo $role)
    {
        $this->roles=$role;
        $this->user=$user;
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function fetchNames($request)
    {
        //
    }

    /**
     * @param $user_id
     * @param $package_id
     * @return bool
     */
    public function assign($user_id, $package_id){
        $package_name   =   strtolower(Config::get('constant.packages.'.$package_id));
        $user           =   $this->user->findById($user_id);
        $assignRole     =   $user->assignRole($package_name);
        return true;
    }
    public function checkRole($user_id){
        $user=$this->user->findById($user_id);
        $user->hasAnyRole(Role::all());
        return $user['roles'];
    }
}