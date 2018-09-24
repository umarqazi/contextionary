<?php
/**
 * Author: Muhammad Adeel
 * Date: 8/13/18
 * Time: 3:10 PM
 */

namespace App\Services;


use App\Repositories\SettingRepo;

class SettingService extends BaseService implements IService
{
    /**
     * @var ContactUsRepo
     */
    protected $setting_repo;

    /**
     * ContactUsService constructor.
     */
    public function __construct()
    {
        $setting_repo = new SettingRepo();
        $this->setting_repo =   $setting_repo;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getListing(){
        return $this->setting_repo->getListing();
    }

}