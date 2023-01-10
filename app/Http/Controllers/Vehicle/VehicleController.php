<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Core\DatagridFilterRequest;
use App\Http\Resources\Auth\AuthLoginResource;
use App\Http\Resources\Player\PlayerResource;
use App\Http\Resources\Profile\ProfileInfoResource;
use App\Http\Resources\Vehicle\VehicleResource;
use App\Http\Services\Auth\AuthLoginService;
use App\Models\AdminRank;
use App\Models\Player;
use App\Models\Vehicle;
use App\Providers\DatagridFilterServiceProvider;
use App\Services\DatagridFilterService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class VehicleController
 * @package App\Http\Controllers\Vehicle
 */
class VehicleController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        private Vehicle $vehicleRepository,
        private DatagridFilterService $datagridFilterService
    ) {}

    /**
     * Returns list of all vehicles.
     *
     * @param DatagridFilterRequest $request
     * @return JsonResponse
     */
    public function list(DatagridFilterRequest $request): JsonResponse
    {
        $queryBuilder = $this->vehicleRepository::query();

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
     * Gets a specific vehicle.
     *
     * @param Request $request
     * @param int $id
     * @return VehicleResource
     */
    public function get(Request $request, int $id): VehicleResource
    {
        $vehicle = $this->vehicleRepository::query()
            ->where('id', $id)
            ->first();

        return VehicleResource::make($vehicle);
    }
}
