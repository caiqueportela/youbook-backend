<?php

namespace App\Validator\Exception;

class UserAlreadyHaveTheCourse extends \Exception
{

    public function __construct($message = "")
    {
        parent::__construct($message, 400, null);
    }

}