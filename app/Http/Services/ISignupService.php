<?php
/**
 * Created by PhpStorm.
 * User: fahad
 * Date: 07/08/2018
 * Time: 6:03 PM
 */

namespace App\Http\Services;


interface ISignupService extends IBusinessService
{

    public function verify_email($email);

    public function process($params);

    public function validate($params);

}