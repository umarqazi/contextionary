<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/9/18
 * Time: 8:11 PM
 */

namespace App\Repositories;
use Spatie\Permission\Models\Role;

class RoleRepo
{
    private $role;
    protected $user;
    public function __construct(Role $role, UserRepo $user)
    {
        $this->role = $role;
        $this->user = $user;
    }
    public function getName($name){

        return $getRoleName=Role::where('name',$name)->first();

    }
    public function assignMultiRole($id, $roles){
        $user           =   $this->user->findById($id);
        $assignRole     =   $user->assignRole($roles);
        return $assignRole;
    }
}