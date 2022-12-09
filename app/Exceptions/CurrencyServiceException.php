<?php

namespace App\Exceptions;


class CurrencyServiceException extends \Exception
{
    const USER_ERROR = 1;
    const SERVER_ERROR = 2;

    public function render()
    {
        return response(
            [
                'message' => $this->getMessage(),
                'status' => $this->getStatusCode()
            ],
            $this->getStatusCode()
        );
    }

    public function getStatusCode(): int
    {
        return $this->code===1 ? 422 : 500;
    }
}
