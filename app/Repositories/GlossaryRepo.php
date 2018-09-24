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
 * Time: 13:12
 */

namespace App\Repositories;


use App\Glossary;

class GlossaryRepo extends BaseRepo implements IRepo
{
    /**
     * @var Glossary
     */
    protected $glossary;

    /**
     * GlossaryRepo constructor.
     */
    public function __construct()
    {
        $glossary = new Glossary();
        $this->glossary = $glossary;
    }

    /**
     * @return mixed
     */
    public function getListing()
    {
        return $this->glossary->listing();
    }

    /**
     * @return mixed
     */
    public function getListingForAuthUser($user)
    {
        return $user->glossary()->paginate();
    }

    /**
     * @return mixed
     */
    public function addToFav($user, $glossary_item_id)
    {
        return $user->glossary()->attach($glossary_item_id);
    }

    /**
     * @return mixed
     */
    public function removeFromFav($user, $glossary_item_id)
    {
        return $user->glossary()->detach($glossary_item_id);
    }
}