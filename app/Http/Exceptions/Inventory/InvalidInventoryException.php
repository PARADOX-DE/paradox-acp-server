<?php

namespace App\Http\Exceptions\Inventory;

use Illuminate\Http\Response;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class InvalidInventoryException
 * @package App\Http\Exceptions\Inventory
 */
class InvalidInventoryException extends RuntimeException
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(string $type = 'Inventar nicht gefunden!')
    {
        parent::__construct($type, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
