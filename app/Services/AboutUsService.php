<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 * Date: 03/09/18
 * Time: 16:08
 */

namespace App\Services;


use App\Repositories\AboutUsRepo;

class AboutUsService extends BaseService implements IService
{
    /**
     * @var AboutUsRepo
     */
    protected $about_u_repo;

    /**
     * AboutUsService constructor.
     */
    public function __construct()
    {
        $about_u_repo = new AboutUsRepo();
        $this->about_u_repo = $about_u_repo;
    }

    /**
     * @return mixed
     */
    public function first(){
        return $this->about_u_repo->first();
    }
}