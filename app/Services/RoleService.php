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
    public function __construct()
    {
        $this->user     =   new UserRepo();
        $this->roles    =   new RoleRepo();
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

    public function fetchNames($request)
    {
        // TODO: Implement fetchNames() method.
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function checkRole($user_id){
        $user=$this->user->findById($user_id);
        $user->hasAnyRole(Role::all());
        return $user['roles'];
    }
}