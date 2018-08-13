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

    public function __construct(Role $role)
    {
        $this->role = $role;
    }
    public function getName($name){

        return $getRoleName=Role::where('name',$name)->first();

    }
}