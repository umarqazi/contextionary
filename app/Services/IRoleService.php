<?php
/**
 * Created by PhpStorm.
 * User: haris
 * Date: 09/08/18
 * Time: 15:31
 */

namespace App\Services;


use App\User;

interface IRoleService extends IService
{
    /**
     * @param User $user
     * @return mixed
     */
    public function fetchNames($request);

    /**
     * @param $user_id
     * @param $package_id
     * @return mixed
     */
    public function assign($user_id, $package_id);
}