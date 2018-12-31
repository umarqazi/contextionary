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


use App\Tutorial;

class TutorialsRepo extends BaseRepo implements IRepo
{
    /**
     * @var Tutorial
     */
    protected $tutorials;

    /**
     * TutorialsRepo constructor.
     */
    public function __construct()
    {
        $tutorials = new Tutorial();
        $this->tutorials = $tutorials;
    }

    /**
     * @return mixed
     */
    public function firstUser(){
        return $this->tutorials->where('type', 'user')->first();
    }

    /**
     * @return mixed
     */
    public function firstContributor(){
        return $this->tutorials->where('type', 'contributor')->first();
    }
}