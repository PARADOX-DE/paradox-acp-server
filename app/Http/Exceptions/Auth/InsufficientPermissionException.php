<?php

namespace App\Http\Exceptions\Auth;

use Illuminate\Http\Response;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class InsufficientPermissionException
 * @package App\Http\Exceptions\Auth
 */
class InsufficientPermissionException extends RuntimeException
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(string $type = 'Dafür hast du nicht die benötigten Rechte.')
    {
        parent::__construct($type, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
