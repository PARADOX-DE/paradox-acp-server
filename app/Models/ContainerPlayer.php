<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ContainerPlayer extends Model
{
    public $timestamps = false;

    /**
     * The table that is targeted.
     * @var string
     */
    protected $table = 'container_player';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'type',
        'max_weight',
        'max_slots',

        'slot_0',
        'slot_1',
        'slot_2',
        'slot_3',
        'slot_4',
        'slot_5',
        'slot_6',
        'slot_7',
        'slot_8',
        'slot_9',
        'slot_10',
        'slot_11',
        'slot_12',
        'slot_13',
        'slot_14',
        'slot_15',
        'slot_16',
        'slot_17',
        'slot_18',
        'slot_19',
        'slot_20',
        'slot_21',
        'slot_22',
        'slot_23',
        'slot_24',
        'slot_25',
        'slot_26',
        'slot_27',
        'slot_28',
        'slot_29',
        'slot_30',
        'slot_31',
        'slot_32',
        'slot_33',
        'slot_34',
        'slot_35',
        'slot_36',
        'slot_37',
        'slot_38',
        'slot_39',
        'slot_40',
        'slot_41',
        'slot_42',
        'slot_43',
        'slot_44',
        'slot_45',
        'slot_46',
        'slot_47'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'marker',
        'events',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

    ];
}
