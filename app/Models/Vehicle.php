<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class Vehicle extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    /**
     * The table that is targeted.
     * @var string
     */
    protected $table = 'vehicles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'model',
        'vehiclehash',
        'owner',
        'fuel',
        'tuning',
        'color1',
        'color2',
        'zustand',
        'inGarage',
        'km',
        'garage_id',
        'plate',
        'note'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'team_id',
        'pos_x',
        'pos_y',
        'pos_z',
        'heading',
        'Neon',
        'serverId',
        'vgarage',
        'WheelClamp',
        'alarm_system',
        'team_present',
        'alarmanlage',
        'dimension',
        'health_data'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /*public $appends = ['owner_name', 'model_name'];

    public function getOwnerNameAttribute()
    {
        $player = $this->player();
        if($player == null)
            return "Unknown";

        return $player->Name;
    }

    public function getModelNameAttribute()
    {
        $vehicleData = $this->vehicleData();
        if($vehicleData == null)
            return "Unknown";

        if($vehicleData->mod_car == 1)
            return $vehicleData->mod_car_name;

        return $vehicleData->car_name;
    }*/

    /**
     * Get the vehicle data.
     */
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class, 'owner', 'id');
    }

    /**
     * Get the vehicle data.
     */
    public function vehicleData(): BelongsTo
    {
        return $this->belongsTo(VehicleData::class, 'model', 'id');
    }

    /**
     * Get the inventory container.
     */
    public function container()
    {
        return ContainerVehicle::query()->where('id', $this->id)->first();
    }
}
