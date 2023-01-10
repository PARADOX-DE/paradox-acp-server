<?php

namespace App\Http\Resources\Team;

use App\Models\Team;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @mixin Team
 */
class TeamResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'name' => $this->name,
            'shortName' => $this->name_short,
            'rgbColor' => $this->rgb,
            'leader' => $this->players()->where('rang', 12)->first('Name')->Name ?? "Unbekannt",

            'packets' => $this->ammoArmory()->packets ?? 0,
            'balance' => $this->shelter()->money,

            'medicSlots' => $this->medicslotsused,
            'medicMaxSlots' => $this->medicslots,

            'memberCount' => $this->players()->count(),
            'maxCount' => $this->max_slots,


            'note' => $this->note,
            'radioFrequencies' => $this->frequenzen,

            'isDutyTeam' => $this->hasDuty,
            'isGangster' => $this->isGangster,
            'isActive' => $this->isActive,

            'warns' => $this->warns,
        ];
    }
}
