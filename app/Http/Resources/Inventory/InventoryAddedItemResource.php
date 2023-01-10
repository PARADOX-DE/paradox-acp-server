<?php

namespace App\Http\Resources\Inventory;

use App\Models\Player;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class InventoryAddedItemResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "item" => $this->resource,
            "message" => "Item wurde hinzugefügt."
        ];
    }
}
