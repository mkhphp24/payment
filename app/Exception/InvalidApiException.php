<?php


namespace App\Exception;

use Exception;

class InvalidApiException extends Exception
{
    public function errorMessage() {
        $errorMsg = 'Bad Request';
        return $errorMsg;
    }

}
