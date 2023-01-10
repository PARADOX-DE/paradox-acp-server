<?php

namespace App\Http\Exceptions\Vehicle;

use Illuminate\Http\Response;
use JetBrains\PhpStorm\Pure;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class InvalidVehicleException
 * @package App\Http\Exceptions\Vehicle
 */
class InvalidVehicleException extends RuntimeException
{
    /**
     * __construct
     *
     * @return void
     */
    #[Pure] public function __construct(string $type = 'Vehicle not found.')
    {
        parent::__construct($type, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
