<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Http\Exceptions\Auth\InsufficientPermissionException;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Core\DatagridFilterRequest;
use App\Http\Resources\Auth\AuthLoginResource;
use App\Http\Resources\Player\PlayerInventoryAddedItemResource;
use App\Http\Resources\Player\PlayerResource;
use App\Http\Resources\Profile\ProfileInfoResource;
use App\Http\Services\Auth\AuthLoginService;
use App\Models\AdminRank;
use App\Models\LogItem;
use App\Models\LogKill;
use App\Models\Player;
use App\Providers\DatagridFilterServiceProvider;
use App\Services\DatagridFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class PlayerLogsItemsController
 * @package App\Http\Controllers\Player
 */
class PlayerLogsItemsController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        private Player $userRepository,
        private LogItem $logItemRepository,
        private DatagridFilterService $datagridFilterService
    ) {}

    public function list(DatagridFilterRequest $request, int $id): \Illuminate\Http\JsonResponse
    {
        if($request->user()
                ->rankId <= 1) throw new InsufficientPermissionException();

        /** @var Player $player */
        $player = $this->userRepository::query()
            ->where('id', $id)
            ->first(['name']);

        $queryBuilder = $this->logItemRepository::query()
            ->where('player_name', $player->name);

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
}
