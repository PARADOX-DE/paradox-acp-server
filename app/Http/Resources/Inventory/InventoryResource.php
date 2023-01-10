<?php

namespace App\Http\Resources\Inventory;

use App\Models\Player;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class InventoryResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'maxSlots' => $this->max_slots,
            'slots' => $this->slots
        ];
    }
}
