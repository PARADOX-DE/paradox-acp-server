<?php

namespace App\Http\Services\Auth;

use App\Http\Exceptions\Auth\InvalidAuthCredentialsException;
use App\Models\Player;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use LogicException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class AuthLoginService
 * @package App\Http\Services\Auth
 */
class AuthLoginService
{
    /**
     * AuthLoginService constructor
     *
     * @return void
     */
    public function __construct(
        private Player $userRepository,

        private Hash $hash,
    ) { }

    /**
     * Compares the password.
     *
     * @param string $password
     * @param string $hash
     * @param string $salt
     * @return bool
     */
    private function comparePassword(string $password, string $hash, string $salt)
    {
        return $hash == hash('sha256',
            $salt . hash('sha256', $password)
        );
    }

    /**
     * Executes the login service.
     *
     * @param string $username
     * @param string $password
     * @return User
     * @throws InvalidArgumentException
     * @throws InvalidAuthCredentialsException
     * @throws MassAssignmentException
     * @throws InvalidCastException
     * @throws LogicException
     */
    public function execute(string $username, string $password)
    {
        /** @var Player */
        $user = $this->userRepository::query()->where('Name', $username)->first();

        if(!$user)
            throw new InvalidAuthCredentialsException('Not exists.');

        if(!$this->comparePassword($password, $user->Pass, $user->Salt))
            throw new InvalidAuthCredentialsException('Password mismatch.');

        // To ensure that only one person per person is logged in.
        if($user->tokens()->count() > 0)
            $user->tokens()->delete();

        return $user;
    }
}
