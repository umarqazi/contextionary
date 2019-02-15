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


use App\Repositories\ContentManagementRepo;

class ContentManagementService extends BaseService implements IService
{
    /**
     * @var ContentManagementRepo
     */
    protected $content_management_repo;

    /**
     * ContentManagementService constructor.
     */
    public function __construct()
    {
        $content_management_repo        = new ContentManagementRepo();
        $this->content_management_repo  = $content_management_repo;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function find($data){
        return $this->content_management_repo->find($data);
    }
}