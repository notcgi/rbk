<?php

namespace App\src\Application;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CurrencyClientException extends HttpException
{
    public function __construct(
        \Throwable $previous = null,
    ) {
        parent::__construct(Response::HTTP_SERVICE_UNAVAILABLE, 'CBR Server Error', $previous, [], 0);
    }

}
