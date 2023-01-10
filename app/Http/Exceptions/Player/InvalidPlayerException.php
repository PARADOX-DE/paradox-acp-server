<?php

namespace App\Http\Exceptions\Player;

use Illuminate\Http\Response;
use JetBrains\PhpStorm\Pure;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class InvalidPlayerException
 * @package App\Http\Exceptions\Player
 */
class InvalidPlayerException extends RuntimeException
{
    /**
     * __construct
     *
     * @return void
     */
    #[Pure] public function __construct(string $type = 'Player not found.')
    {
        parent::__construct($type, Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
