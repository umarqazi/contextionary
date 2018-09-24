<?php
/**
 * Created by PhpStorm.
 * User: adi
 * Date: 8/13/18
 * Time: 3:12 PM
 */

namespace App\Repositories;

use App\Setting;
use App\Services\BaseService;
use App\Services\IService;

class SettingRepo extends BaseService implements IService
{
    /**
     * @var Setting
     */
    protected $setting;

    /**
     * SettingRepo constructor.
     */
    public function __construct()
    {
        $setting = new Setting();
        $this->setting = $setting;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getListing(){
        return $this->setting->getListing();
    }
}