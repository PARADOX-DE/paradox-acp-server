<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Core\DatagridFilterRequest;
use App\Http\Requests\Team\TeamMemberRequest;
use App\Http\Resources\Auth\AuthLoginResource;
use App\Http\Resources\Core\CallbackMessageResource;
use App\Http\Resources\Player\PlayerInventoryAddedItemResource;
use App\Http\Resources\Player\PlayerResource;
use App\Http\Resources\Profile\ProfileInfoResource;
use App\Http\Resources\Team\TeamMemberResource;
use App\Http\Services\Auth\AuthLoginService;
use App\Models\AdminRank;
use App\Models\LogKill;
use App\Models\Player;
use App\Models\Team;
use App\Models\TeamVehicle;
use App\Models\Vehicle;
use App\Providers\DatagridFilterServiceProvider;
use App\Services\DatagridFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class TeamMembersController
 * @package App\Http\Controllers\Team
 */
class TeamMembersController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        private Team $teamRepository,
        private Player $playerRepository,
        private DatagridFilterService $datagridFilterService
    ) {}

    public function list(DatagridFilterRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        /** @var Team $team */
        $team = $this->teamRepository::query()
            ->where('id', $id)
            ->first(['id']);

        $queryBuilder = $this->playerRepository::query()
            ->where('team', $team->id);

        $count = $queryBuilder->count();

        $data = $this->datagridFilterService->filter(
            $queryBuilder,
            $request->get('skip'),
            $request->get('limit'),
            $request->get('filter')
        );

        return response()->json(
            $data
        );
    }

    public function get(TeamMemberRequest $request, int $id): TeamMemberResource
    {
        /** @var Team $team */
        $team = $this->teamRepository::query()
            ->where('id', $id)
            ->first(['id']);

        $player = $this->playerRepository::query()
            ->where('id', $request->get('player'))
            ->where('team', $team->id)
            ->first();

        return TeamMemberResource::make($player);
    }

    public function add(TeamMemberRequest $request, int $id): CallbackMessageResource
    {
        /** @var Team $team */
        $team = $this->teamRepository::query()
            ->where('id', $id)
            ->first(['id', "name"]);

        $playerId = $request->get('player');

        $playerQueryBuilder = $this->playerRepository::query();

        if(is_numeric($playerId))
            $playerQueryBuilder->where('id', $request->get('player'));
        else
            $playerQueryBuilder->where('Name', $request->get('player'));

        $player = $playerQueryBuilder->first();

        return new CallbackMessageResource("{$player} in die Fraktion {$team->name} hinzugefügt.");
    }
}
