<?php

namespace App\src\Application;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CurrencyServiceException extends HttpException
{
    public function __construct(
        string $message = '',
        \Throwable $previous = null,
    ) {
        parent::__construct(Response::HTTP_UNPROCESSABLE_ENTITY, $message, $previous, [], 0);
    }
}
