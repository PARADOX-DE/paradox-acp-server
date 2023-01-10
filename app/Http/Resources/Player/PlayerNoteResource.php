<?php

namespace App\Http\Resources\Player;

use App\Models\Player;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @mixin Player
 */
class PlayerNoteResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'note' => $this->note
        ];
    }
}
