<?php

namespace App\Http\Resources\Core;

use App\Models\Player;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class CallbackMessageResource extends JsonResource
{
    /**
     * @param  Request  $request
     */
    public function toArray($request)
    {
        return [
            "message" => $this->resource
        ];
    }
}
