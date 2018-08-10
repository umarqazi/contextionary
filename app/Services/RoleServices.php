<?php
namespace App\Services;

/**
 * Copyright (c) 2018, fahad-shehzad.com All rights reserved.
 *
 * @author Muhammad Adeel
 * @since Feb 23, 2018
 * @package app.contextionary.services
 * @project starzplay
 *
 */
use App\User;
use Illuminate\Http\Request;
use Config;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Repositories\RoleRepo;

class RoleServices
{
    /**
     * @return int
     */
     protected $role;

     public function __construct(RoleRepo $role)
     {
         $this->role = $role;
     }

}
