<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Core\DatagridFilterRequest;
use App\Http\Resources\Auth\AuthLoginResource;
use App\Http\Resources\Player\PlayerInventoryAddedItemResource;
use App\Http\Resources\Player\PlayerResource;
use App\Http\Resources\Profile\ProfileInfoResource;
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
 * Class TeamVehiclesController
 * @package App\Http\Controllers\Team
 */
class TeamVehiclesController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        private Team $teamRepository,
        private TeamVehicle $vehicleRepository,
        private DatagridFilterService $datagridFilterService
    ) {}

    public function list(DatagridFilterRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        /** @var Team $team */
        $team = $this->teamRepository::query()
            ->where('id', $id)
            ->first(['id', 'name']);

        $queryBuilder = $this->vehicleRepository::query()
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

    /**
     * Add function to create team vehicles
     */
}
