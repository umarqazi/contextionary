<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 * Date: 14/09/18
 * Time: 13:12
 */

namespace App\Repositories;

use Encore\Admin\Auth\Database\Administrator;

class AdminRepo extends BaseRepo implements IRepo
{
    /**
     * @var Administrator
     */
    protected $admin;

    /**
     * AdminRepo constructor.
     */
    public function __construct()
    {
        $admin = new Administrator();
        $this->admin = $admin;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getListing()
    {
        return $this->admin->all();
    }
}