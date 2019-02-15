<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 * Date: 03/09/18
 * Time: 16:20
 */

namespace App\Repositories;


use App\ContentManagement;

class ContentManagementRepo extends BaseRepo implements IRepo
{
    /**
     * @var ContentManagement
     */
    protected $contentManagement;

    /**
     * ContentManagementRepo constructor.
     */
    public function __construct()
    {
        $contentManagement = new ContentManagement();
        $this->contentManagement = $contentManagement;
    }

    /**
     * @param $data
     * @return mixed
     */
    public function find($data){
        return $this->contentManagement->where($data)->first();
    }
}