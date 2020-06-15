<?php

namespace App\Validator\Exception;

class UserIsNotCommentOwner extends \Exception
{

    public function __construct($message = "")
    {
        parent::__construct($message, 403, null);
    }

}