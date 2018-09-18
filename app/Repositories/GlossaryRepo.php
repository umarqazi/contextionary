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
}