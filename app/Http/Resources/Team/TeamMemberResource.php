<?php

namespace App\Http\Resources\Team;

use App\Models\Player;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @mixin Player
 */
class TeamMemberResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'name' => $this->Name,
            'rank' => $this->rang,

            'managePermission' => $this->teamPlayerRights()->r_manage,
            'inventoryPermission' => $this->teamPlayerRights()->r_inventory,
            'bankPermission' => $this->teamPlayerRights()->r_bank,
        ];
    }
}
