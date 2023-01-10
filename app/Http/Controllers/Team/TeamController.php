<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Core\DatagridFilterRequest;
use App\Http\Resources\Auth\AuthLoginResource;
use App\Http\Resources\Player\PlayerResource;
use App\Http\Resources\Profile\ProfileInfoResource;
use App\Http\Resources\Team\TeamResource;
use App\Http\Services\Auth\AuthLoginService;
use App\Models\AdminRank;
use App\Models\Team;
use App\Models\Player;
use App\Providers\DatagridFilterServiceProvider;
use App\Services\DatagridFilterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class TeamController
 * @package App\Http\Controllers\Team
 */
class TeamController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        private Team $factionRepository,
        private DatagridFilterService $datagridFilterService
    ) {}

    /**
     * Returns list of all players.
     *
     * @param DatagridFilterRequest $request
     * @return JsonResponse
     */
    public function list(DatagridFilterRequest $request): JsonResponse
    {
        $queryBuilder = $this->factionRepository::query()
            ->where('isActive', '=', 1);

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

    /**
     * Gets a specific player.
     *
     * @param Request $request
     * @param int $id
     * @return TeamResource
     */
    public function get(Request $request, int $id): TeamResource
    {
        $player = $this->factionRepository::query()
            ->where('id', $id)
            ->first();

        return TeamResource::make($player);
    }
}
