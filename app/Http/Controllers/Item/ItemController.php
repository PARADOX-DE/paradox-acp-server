<?php

namespace App\Http\Controllers\Item;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\AuthLoginRequest;
use App\Http\Requests\Core\DatagridFilterRequest;
use App\Http\Requests\Player\PlayerNoteRequest;
use App\Http\Resources\Auth\AuthLoginResource;
use App\Http\Resources\Core\CallbackMessageResource;
use App\Http\Resources\Item\ItemResource;
use App\Http\Resources\Item\TargetItemResource;
use App\Http\Resources\Player\PlayerNoteResource;
use App\Http\Resources\Player\PlayerResource;
use App\Http\Resources\Profile\ProfileInfoResource;
use App\Http\Services\Auth\AuthLoginService;
use App\Models\AdminRank;
use App\Models\Item;
use App\Models\Player;
use App\Providers\DatagridFilterServiceProvider;
use App\Services\DatagridFilterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class ItemController
 * @package App\Http\Controllers\Item
 */
class ItemController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        private Item $itemRepository,
    ) {}

    public function list(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $items = $this->itemRepository::query()
            ->get();

        return ItemResource::collection($items);
    }

    /**
     * Gets a specific item by id.
     *
     * @param Request $request
     * @param int $id
     * @return TargetItemResource
     */
    public function get(Request $request, int $id): TargetItemResource
    {
        $item = $this->itemRepository::query()
            ->where('id', $id)
            ->first();

        return TargetItemResource::make($item);
    }
}
