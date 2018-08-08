<?php
/**
 * Created by PhpStorm.
 * User: fahad
 * Date: 08/08/2018
 * Time: 5:40 PM
 */

namespace App\Forms;

/**
 * Class SignupForm
 * @package App\Forms
 */
class SignupForm extends BaseForm
{
    public $email;
    public $first_name;
    public $last_name;
    public $password;
    public $confirm_password;


    /**
     * @return bool|void
     */
    public function validate()
    {
        return false;
    }

}