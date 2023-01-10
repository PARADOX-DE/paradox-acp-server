<?php

namespace App\Http\Exceptions\Auth;

use Illuminate\Http\Response;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class InvalidAuthCredentialsException
 * @package App\Http\Exceptions\Auth
 */
class InvalidAuthCredentialsException extends RuntimeException
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(string $type = 'Unknown')
    {
        parent::__construct($type, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
