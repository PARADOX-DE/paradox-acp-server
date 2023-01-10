<?php

namespace App\Http\Controllers\Player;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Core\DatagridFilterRequest;
use App\Http\Requests\Player\PlayerNoteRequest;
use App\Http\Resources\Auth\AuthLoginResource;
use App\Http\Resources\Core\CallbackMessageResource;
use App\Http\Resources\Player\PlayerNoteResource;
use App\Http\Resources\Player\PlayerResource;
use App\Http\Resources\Profile\ProfileInfoResource;
use App\Http\Services\Admin\ExecuteAdminLogService;
use App\Http\Services\Auth\AuthLoginService;
use App\Models\AdminRank;
use App\Models\Player;
use App\Providers\DatagridFilterServiceProvider;
use App\Services\DatagridFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class PlayerNoteController
 * @package App\Http\Controllers\Player
 */
class PlayerNoteController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        private Player $userRepo,
        private ExecuteAdminLogService $adminLogService
    ) {}

    public function get(Request $request, int $id): PlayerNoteResource
    {
        $player = $this->userRepo::query()
            ->where('id', $id)
            ->first("note");

        return PlayerNoteResource::make($player);
    }

    public function update(PlayerNoteRequest $request, int $id): CallbackMessageResource
    {
        $update = $this->userRepo::query()
            ->where('id', $id)
            ->update([
                "note" => $request->get('note')
            ]);

        $this->adminLogService->execute($request->user()->id, $id, 'PLAYER', "Akte geändert.");

        return CallbackMessageResource::make('Akte wurde gespeichert... ' . $update);
    }
}
