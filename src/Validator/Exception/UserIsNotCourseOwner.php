<?php

namespace App\Validator\Exception;

class UserIsNotCourseOwner extends \Exception
{

    public function __construct($message = "")
    {
        parent::__construct($message, 403, null);
    }

}