<?php

namespace App\Http\Services\Admin;

use App\Enumerations\ACPActionType;
use App\Http\Exceptions\Auth\InvalidAuthCredentialsException;
use App\Http\Exceptions\Player\InvalidPlayerException;
use App\Models\ACPAction;
use App\Models\Player;
use Illuminate\Database\Eloquent\MassAssignmentException;
use Illuminate\Database\Eloquent\InvalidCastException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use InvalidArgumentException;
use LogicException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class ExecuteAdminLiveActionService
 * @package App\Http\Services\Admin
 */
class ExecuteAdminLiveActionService
{
    /**
     * ExecuteAdminLiveActionService constructor
     *
     * @return void
     */
    public function __construct(
        private Player $userRepository
    ) { }

    public function execute(int $adminId, int $userId, int $type, string $action = ''): bool
    {
        /** @var Player $player */
        $player = $this->userRepository::query()->where('id', $userId)->first(["id"]);

        if(!$player)
            throw new InvalidPlayerException();

        ACPAction::query()->create([
            'admin_id' => $adminId,
            'player_id' => $userId,
            'action_type' => $type,
            'action' => $action,
        ]);

        return true;
    }
}
