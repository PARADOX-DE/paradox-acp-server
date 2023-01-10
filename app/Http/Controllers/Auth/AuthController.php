<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Exceptions\Auth\InsufficientPermissionException;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Resources\Auth\AuthLoginResource;
use App\Http\Services\Auth\AuthLoginService;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class AuthController
 * @package App\Http\Controllers\Auth
 */
class AuthController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        private AuthLoginService $loginAuthService,
    ) {}

    public function info(Request $request)
    {
        return $request->user();
    }

    public function login(AuthLoginRequest $request): AuthLoginResource
    {
        /** @var Player $player */
        $player = $this->loginAuthService->execute(
            $request->get('username'),
            $request->get('password'),
        );

        if($player->rankId <= 1) throw new InsufficientPermissionException();

        return AuthLoginResource::make($player);
    }
}
