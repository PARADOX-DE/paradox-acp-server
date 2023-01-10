<?php

namespace App\Http\Controllers\Player;

use App\Enumerations\ACPActionType;
use App\Http\Controllers\Controller;
use App\Http\Exceptions\Auth\InsufficientPermissionException;
use App\Http\Exceptions\Inventory\InvalidInventoryException;
use App\Http\Exceptions\Player\InvalidPlayerException;
use App\Http\Requests\Inventory\InventoryAddItemRequest;
use App\Http\Requests\Inventory\InventoryDeleteItemRequest;
use App\Http\Resources\Core\CallbackMessageResource;
use App\Http\Resources\Inventory\InventoryAddedItemResource;
use App\Http\Resources\Inventory\InventoryResource;
use App\Http\Services\Admin\ExecuteAdminLiveActionService;
use App\Http\Services\Admin\ExecuteAdminLogService;
use App\Models\Item;
use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use stdClass;

/**
 * Class PlayerNoteController
 * @package App\Http\Controllers\Player
 */
class PlayerInventoryController extends Controller
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        private Player $userRepo,

        private ExecuteAdminLiveActionService $executeAdminLiveActionService,
        private ExecuteAdminLogService $adminLogService
    ) {}

    /**
     * Get max-slots by inventory type.
     *
     * @param int $type
     * @return int
     */
    private function getMaxSlots(int $type) : int
    {
        return match ($type) {
            8, 2, 43 => 63,
            7 => 109,
            default => 48,
        };
    }

    /**
     * Get player inventory.
     *
     * @param Request $request
     * @param int $id
     * @return InventoryResource
     */
    public function get(Request $request, int $id): InventoryResource
    {
        /** @var Player $player */
        $player = $this->userRepo::query()
            ->where('id', $id)
            ->first();

        if(!$player)
            throw new InvalidPlayerException();

        $container = $player->container();
        if(!$container)
            throw new InvalidInventoryException();

        $items = Collection::make();

        for($i = 0; $i < $this->getMaxSlots($container->type); $i++)
        {
            $itemValue = $container["slot_" . $i];
            if($itemValue == "" || $itemValue == "[]") continue;

            $item = json_decode(
                $container["slot_" . $i]
            )[0];

            $itemModel = Item::query()
                ->where('id', $item->Id)
                ->first(["name", "image_path"]);

            if($itemModel) {
                $items->add([
                    "id" => $item->Id,
                    "name" => $itemModel->name,
                    "amount" => $item->Amount,
                    "imagePath" => $itemModel->image_path,
                    "slot" => $i + 1,
                ]);
            }
        }

        $container->slots = $items;
        return InventoryResource::make($container);
    }

    public function delete(InventoryDeleteItemRequest $request, int $id): CallbackMessageResource
    {
        if($request->user()
            ->rankId <= 2) throw new InsufficientPermissionException();

        /** @var Player $player */
        $player = $this->userRepo::query()
            ->where('id', $id)
            ->first();

        if(!$player)
            throw new InvalidPlayerException();

        $container = $player->container();
        if(!$container)
            throw new InvalidInventoryException();

        $slot = 'slot_' . ($request->get('slot') - 1);

        $itemSlot = $container[$slot];
        if(!$itemSlot)
            throw new InvalidInventoryException("Item wurde nicht gefunden!");

        $container[$slot] = "";
        $container->save();

        if($player->Online == 1)
            $this->executeAdminLiveActionService->execute(
                $request->user()->id,
                $player->id,
                ACPActionType::RELOAD_CONTAINER_PLAYER
            );

        $this->adminLogService->execute($request->user()->id, $player->id, 'PLAYER', "Item Slot: {$slot} gelöscht.");

        return CallbackMessageResource::make("Item wurde entfernt.");
    }


    public function add(InventoryAddItemRequest $request, int $id): InventoryAddedItemResource
    {
        if($request->user()
                ->rankId <= 2) throw new InsufficientPermissionException();

        /** @var Player $player */
        $player = $this->userRepo::query()
            ->where('id', $id)
            ->first();

        if(!$player)
            throw new InvalidPlayerException();

        $container = $player->container();
        if(!$container)
            throw new InvalidInventoryException();

        $itemModel = Item::query()
            ->where('name', $request->get('item'))
            ->first(["id", "name", "image_path"]);

        $targetSlot = 0;
        for($i = 0; $i < $this->getMaxSlots($container->type); $i++)
        {
            if($i + 1 > $container->max_slots) continue;

            $item = json_decode(
                $container["slot_" . $i]
            );

            if($item == null || $item == [] || $item[0]->Id == 0) {
                $container["slot_" . $i] = json_encode(
                    [
                        [
                            "Id" => $itemModel->id,
                            "Durability" => 1,
                            "Amount" => $request->get("amount"),
                            "Data" => new stdClass()
                        ]
                    ]
                );
                $targetSlot = $i;
                break;
            }
        }

        $container->save();

        $item = [
            "id" => $itemModel->id,
            "name" => $itemModel->name,
            "amount" => $request->get("amount"),
            "imagePath" => $itemModel->image_path,
            "slot" => $targetSlot + 1,
        ];

        if($player->Online == 1)
            $this->executeAdminLiveActionService->execute(
                $request->user()->id,
                $player->id,
                ACPActionType::RELOAD_CONTAINER_PLAYER
            );

        $this->adminLogService->execute($request->user()->id, $player->id, 'PLAYER', "{$request->get('amount')}x {$itemModel->name} hinzugefügt.");

        return InventoryAddedItemResource::make($item);
    }

    public function clear(Request $request, int $id): CallbackMessageResource
    {
        if($request->user()
                ->rankId <= 2) throw new InsufficientPermissionException();

        /** @var Player $player */
        $player = $this->userRepo::query()
            ->where('id', $id)
            ->first();

        if(!$player)
            throw new InvalidPlayerException();

        $container = $player->container();
        if(!$container)
            throw new InvalidInventoryException();

        for($i = 0; $i < $this->getMaxSlots($container->type); $i++)
        {
            $itemValue = $container["slot_" . $i];
            if($itemValue == "" || $itemValue == "[]") continue;

            $container["slot_" . $i] = "";
        }

        $container->save();

        if($player->Online == 1)
            $this->executeAdminLiveActionService->execute(
                $request->user()->id,
                $player->id,
                ACPActionType::RELOAD_CONTAINER_PLAYER
            );

        $this->adminLogService->execute($request->user()->id, $player->id, 'PLAYER', "Inventar geleert.");

        return CallbackMessageResource::make("Inventar wurde geleert.");
    }
}
