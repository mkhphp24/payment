<?php


namespace App\Exception;

use Exception;

class InvalidDbException extends Exception
{
    public function errorMessage() {
        $errorMsg = 'Bad query';
        return $errorMsg;
    }

}
