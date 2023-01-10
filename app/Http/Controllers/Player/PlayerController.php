<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Core\DatagridFilterRequest;
use App\Http\Resources\Auth\AuthLoginResource;
use App\Http\Resources\Player\PlayerResource;
use App\Http\Resources\Profile\ProfileInfoResource;
use App\Http\Services\Auth\AuthLoginService;
use App\Models\AdminRank;
use App\Models\Player;
use App\Providers\DatagridFilterServiceProvider;
use App\Services\DatagridFilterService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class PlayerController
 * @package App\Http\Controllers\Player
 */
class PlayerController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        private Player $userRepo,
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
        $queryBuilder = $this->userRepo::query();
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
     * @return PlayerResource
     */
    public function get(Request $request, int $id): PlayerResource
    {
        $player = $this->userRepo::query()
            ->where('id', $id)
            ->first();

        return PlayerResource::make($player);
    }
}
