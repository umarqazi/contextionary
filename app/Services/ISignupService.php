<?php
/**
 * Created by PhpStorm.
 * User: fahad
 * Date: 07/08/2018
 * Time: 6:03 PM
 */

namespace App\Services;


interface ISignupService extends IBusinessService
{

    /**
     * @param $email
     * @return mixed
     */
    public function verify_email($email);

    /**
     * @param $signupForm \App\Forms\SignupForm
     * @return mixed
     */
    public function asContributor($signupForm);

    /**
     * @param $params
     * @return mixed
     */
    public function asConsumer($params);


}