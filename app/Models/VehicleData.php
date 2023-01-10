<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class VehicleData extends Model
{
    public $timestamps = false;

    /**
     * The table that is targeted.
     * @var string
     */
    protected $table = 'vehicledata';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'model',
        'price',
        'multiplier',
        'fuel',
        'fuel_consumption',
        'classification',
        'mod_car',
        'mod_car_name',
        'car_name',
        'max_speed'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        "carsell_category",
        "is_business_vehicle",
        "liveryindex",
        "vehdoor_trunk",
        "vehdoor_trunk2",
        "has_siren",
        "fuel",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];
}
