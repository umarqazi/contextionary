<?php
/**
 * Created by PhpStorm.
 * User: fahad
 * Date: 07/08/2018
 * Time: 6:04 PM
 */

namespace App\Services;


class SignupServce extends BaseBusinessClass implements ISignupService
{


    /**
     * @param $email
     * @return mixed
     */
    public function verify_email($email)
    {
        // TODO: Implement verify_email() method.
    }


    /**
     * @param $params
     * @return mixed
     */
    public function asConsumer($params)
    {
        // TODO: Implement asConsumer() method.
    }

    /**
     * @param $signupForm \App\Forms\SignupForm
     * @return mixed
     */
    public function asContributor($signupForm)
    {
        if(!$signupForm->validate()){
            return $signupForm->errors();
        }

    }
}