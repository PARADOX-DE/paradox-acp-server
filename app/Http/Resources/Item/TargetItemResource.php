<?php

namespace App\Http\Resources\Item;

use App\Models\Player;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class TargetItemResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "imagePath" => $this->image_path,
        ];
    }
}
