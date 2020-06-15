<?php

namespace App\Validator\Exception;

class PostNotFound extends \Exception
{

    public function __construct($message = "")
    {
        parent::__construct($message, 404, null);
    }

}