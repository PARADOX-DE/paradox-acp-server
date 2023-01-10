<?php

namespace App\Http\Controllers\Player;

use App\Enumerations\ACPActionType;
use App\Http\Controllers\Controller;
use App\Http\Exceptions\Auth\InsufficientPermissionException;
use App\Http\Exceptions\Player\InvalidPlayerException;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Core\DatagridFilterRequest;
use App\Http\Requests\Player\PlayerNoteRequest;
use App\Http\Resources\Auth\AuthLoginResource;
use App\Http\Resources\Core\CallbackMessageResource;
use App\Http\Resources\Player\PlayerNoteResource;
use App\Http\Resources\Player\PlayerResource;
use App\Http\Resources\Profile\ProfileInfoResource;
use App\Http\Services\Admin\ExecuteAdminLiveActionService;
use App\Http\Services\Admin\ExecuteAdminLogService;
use App\Http\Services\Auth\AuthLoginService;
use App\Models\AdminRank;
use App\Models\Player;
use App\Models\SocialBan;
use App\Providers\DatagridFilterServiceProvider;
use App\Services\DatagridFilterService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class PlayerActionController
 * @package App\Http\Controllers\Player
 */
class PlayerActionController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        private Player $userRepository,

        private ExecuteAdminLiveActionService $executeAdminLiveActionService,
        private ExecuteAdminLogService $adminLogService
    ) {}

    public function kick(Request $request, int $id): CallbackMessageResource
    {
        if($request->user()
                ->rankId <= 1) throw new InsufficientPermissionException();

        $player = $this->userRepository::query()
            ->where('id', $id)
            ->first(["id", "Online"]);

        if(!$player)
            throw new InvalidPlayerException();

        if($player->Online == 0)
            return CallbackMessageResource::make('Spieler ist nicht online!');

        $this->executeAdminLiveActionService->execute(
            $request->user()->id,
            $player->id,
            ACPActionType::KICK,
            $request->get('reason')
        );

        $this->adminLogService->execute($request->user()->id, $player->id, 'PLAYER', 'Spieler gekickt.');

        return CallbackMessageResource::make('Spieler wurde gekickt!');
    }

    public function timeBan(Request $request, int $id): CallbackMessageResource
    {
        if($request->user()
                ->rankId <= 1) throw new InsufficientPermissionException();

        $player = $this->userRepository::query()
            ->where('id', $id)
            ->first(["id", "Online", "timeban"]);

        if(!$player)
            throw new InvalidPlayerException();

        $timeBanHours = (int)$request->get("duration");
        $timeBanReason = $request->get("reason");

        if($player->Online == 1)
            $this->executeAdminLiveActionService->execute(
                $request->user()->id,
                $player->id,
                ACPActionType::KICK,
                "Timeban: {$timeBanHours} Stunden wegen {$timeBanReason}"
            );

        $player->timeban = Carbon::now()
            ->addHours($timeBanHours)
            ->timestamp;

        $player->save();
        $this->adminLogService->execute($request->user()->id, $player->id, 'PLAYER', 'Spieler temporär gebannt (' . $timeBanHours . 'h).');

        return CallbackMessageResource::make('Spieler wurde temporär gebannt!');
    }

    public function warn(Request $request, int $id): CallbackMessageResource
    {
        if($request->user()
                ->rankId <= 1) throw new InsufficientPermissionException();

        $player = $this->userRepository::query()
            ->where('id', $id)
            ->first(["id", "Online", "warns"]);

        if(!$player)
            throw new InvalidPlayerException();

        $warnReason = $request->get("reason");

        if($player->Online == 1)
            $this->executeAdminLiveActionService->execute(
                $request->user()->id,
                $player->id,
                ACPActionType::WARN,
                $warnReason
            );

        if(($player->warns + 1) >= 3)
        {
            if($player->Online == 1)
                $this->executeAdminLiveActionService->execute(
                    $request->user()->id,
                    $player->id,
                    ACPActionType::SUSPEND
                );

            $player->ausschluss = 1;
        }

        $player->warns = $player->warns + 1;
        $player->save();

        $this->adminLogService->execute($request->user()->id, $player->id, 'PLAYER', "Spieler verwarnt ({$player->warns}/3).");

        return CallbackMessageResource::make("Spieler wurde verwarnt! - ({$player->warns}/3)");
    }

    public function suspend(Request $request, int $id): CallbackMessageResource
    {
        if($request->user()
                ->rankId <= 3) throw new InsufficientPermissionException();

        $player = $this->userRepository::query()
            ->where('id', $id)
            ->first(["id", "Online", "ausschluss", "SCName"]);

        if(!$player)
            throw new InvalidPlayerException();

        if($player->Online == 1)
            $this->executeAdminLiveActionService->execute(
                $request->user()->id,
                $player->id,
                ACPActionType::SUSPEND
            );

        $player->ausschluss = 1;
        $player->save();

        SocialBan::query()
            ->create(['Name' => $player->SCName, "Hwid" => ""]);

        $this->adminLogService->execute($request->user()->id, $player->id, 'PLAYER', "Spieler ausgeschlossen.");

        return CallbackMessageResource::make('Spieler wurde aus der Community ausgeschlossen!');
    }

    public function deSuspend(Request $request, int $id): CallbackMessageResource
    {
        if($request->user()
                ->rankId <= 3) throw new InsufficientPermissionException();

        $player = $this->userRepository::query()
            ->where('id', $id)
            ->first(["id", "Online", "ausschluss", "warns", "SCName"]);

        if(!$player)
            throw new InvalidPlayerException();

        if($player->ausschluss == 0 && $player->warns < 3)
            return CallbackMessageResource::make('Spieler ist nicht gebannt!');

        $player->ausschluss = 0;
        $player->warns = 0;
        $player->save();

        SocialBan::query()
            ->where('Name', $player->SCName)
            ->delete();

        $this->adminLogService->execute($request->user()->id, $player->id, 'PLAYER', "Spieler-Ausschluss aufgehoben.");

        return CallbackMessageResource::make('Community-Ausschluss wurde aufgehoben!');
    }

    public function reloadInventory(Request $request, int $id): CallbackMessageResource
    {
        if($request->user()
                ->rankId <= 3) throw new InsufficientPermissionException();

        $player = $this->userRepository::query()
            ->where('id', $id)
            ->first(["id", "Online"]);

        if(!$player)
            throw new InvalidPlayerException();

        if($player->Online == 0)
            return CallbackMessageResource::make('Spieler ist nicht online!');

        $this->executeAdminLiveActionService->execute(
            $request->user()->id,
            $player->id,
            ACPActionType::RELOAD_CONTAINER_PLAYER
        );

        return CallbackMessageResource::make('Spieler-Inventar neu geladen!');
    }

    public function callToSupport(Request $request, int $id): CallbackMessageResource
    {
        $player = $this->userRepository::query()
            ->where('id', $id)
            ->first(["id", "Online"]);

        if(!$player)
            throw new InvalidPlayerException();

        if($player->Online == 0)
            return CallbackMessageResource::make('Spieler ist nicht online!');

        $this->executeAdminLiveActionService->execute(
            $request->user()->id,
            $player->id,
            ACPActionType::CALL_TO_SUPPORT
        );

        $this->adminLogService->execute($request->user()->id, $player->id, 'PLAYER', "Spieler in Support gerufen.");

        return CallbackMessageResource::make('Spieler wurde in Support gerufen!');
    }
}
