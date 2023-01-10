<?php

namespace App\Http\Resources\Player;

use App\Models\Player;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @mixin Player
 */
class PlayerResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,

            'isOnline' => $this->Online,

            'username' => $this->Name,
            'adminRank' => $this->adminRank()->name,
            'forumId' => $this->forumid,

            'money' => $this->Money,
            'bankMoney' => $this->BankMoney,
            'blackMoney' => $this->blackmoney,

            'level' => $this->Level,
            'job' => $this->job,
            'cuffed' => $this->isCuffed,
            'tied' => $this->isTied,
            'faction' => $this->faction()->name,
            'phoneNumber' => $this->handy <= 0 ? (1275 + $this->id) : $this->handy,

            'socialClubName' => $this->SCName,
            'warns' => $this->warns,
            'suspended' => $this->ausschluss,

            'birthdate' => $this->birthday,
            'lastLogin' => $this->LastLogin,
        ];
    }
}
