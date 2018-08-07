<?php
/**
 * Created by PhpStorm.
 * User: fahad
 * Date: 07/08/2018
 * Time: 6:02 PM
 */

namespace App\Http\Services;


abstract class AbstractDBService implements IDBService
{

    /**
     * without pagnation...
     *
     * @param $params
     * @return mixed
     */
    public function getList($params)
    {
        return $this->search($params);
    }
}