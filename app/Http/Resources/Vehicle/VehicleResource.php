<?php

namespace App\Http\Resources\Vehicle;

use App\Models\Player;
use App\Models\Vehicle;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

/**
 * @mixin Vehicle
 */
class VehicleResource extends JsonResource
{
    /**
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'owner' => $this->player->Name,
            'ownerId' => $this->player->id,
            'vehiclehash' => $this->vehiclehash,
            'modelName' => $this->vehicleData->car_name,
            'isModCar' => $this->vehicleData->mod_car,
            'price' => $this->vehicleData->price,
            'maxSpeed' => $this->vehicleData->max_speed,
            'plate' => $this->plate,
            'note' => $this->note,
            'garageId' => $this->garage_id,
            'inGarage' => $this->inGarage == 1
        ];
    }
}
