<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 * Date: 14/09/18
 * Time: 13:19
 */

namespace App\Services;


use App\Repositories\AdminRepo;

class AdminService extends BaseService implements IService
{
    /**
     * @var AdminRepo
     */
    protected $admin_repo;

    /**
     * AdminService constructor.
     */
    public function __construct()
    {
        $admin_repo = new AdminRepo();
        $this->admin_repo = $admin_repo;
    }

    /**
     * @return mixed
     */
    public function getListing(){
        return $this->admin_repo->getListing();
    }


}