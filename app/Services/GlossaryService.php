<?php
/**
 * @author haris
 * @package
 * @copyright 2018 Techverx.com
 * @project contextionary
 */


/**
 * Created by PhpStorm.
 * User: haris
 * Date: 14/09/18
 * Time: 13:19
 */

namespace App\Services;


use App\Repositories\GlossaryRepo;

class GlossaryService extends BaseService implements IService
{
    /**
     * @var GlossaryRepo
     */
    protected $glossary_repo;

    /**
     * GlossaryService constructor.
     */
    public function __construct()
    {
        $glossary_repo = new GlossaryRepo();
        $this->glossary_repo = $glossary_repo;
    }

    /**
     * @return mixed
     */
    public function getListing(){
        return $this->glossary_repo->getListing();
    }

    /**
     * @return mixed
     */
    public function getListingForAuthUser($user){
        return $this->glossary_repo->getListingForAuthUser($user);
    }

    /**
     * @return mixed
     */
    public function addToFav($user, $glossary_item_id){
        return $this->glossary_repo->addToFav($user, $glossary_item_id);
    }

    /**
     * @return mixed
     */
    public function removeFromFav($user, $glossary_item_id){
        return $this->glossary_repo->removeFromFav($user, $glossary_item_id);
    }

}