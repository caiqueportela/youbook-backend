<?php

namespace App\Validator\Exception;

class ChapterNotFound extends \Exception
{

    public function __construct($message = "")
    {
        parent::__construct($message, 404, null);
    }

}