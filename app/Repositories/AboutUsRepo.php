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


use App\AboutUs;

class AboutUsRepo extends BaseRepo implements IRepo
{
    /**
     * @var AboutUs
     */
    protected $aboutUs;

    /**
     * AboutUsRepo constructor.
     */
    public function __construct()
    {
        $aboutUs = new AboutUs();
        $this->aboutUs = $aboutUs;
    }

    /**
     * @return mixed
     */
    public function first(){
        return $this->aboutUs->first();
    }
}