<?php
/**
 * Created by PhpStorm.
 * User: fahad
 * Date: 07/08/2018
 * Time: 3:56 PM
 */

namespace App\Http\Services;


interface IDBService
{
    /**
     *
     */
    public function persist( $params );

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id);

    /**
     * @param $params
     * @return mixed
     */
    public function update($params);

    public function remove($id);

    /**
     * Paginated data based on params.
     * @param $params
     * @return mixed
     */
    public function search( $params );

    /**
     * without pagnation...
     *
     * @param $params
     * @return mixed
     */
    public function getList( $params );
}