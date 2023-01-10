<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class LogKill extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    /**
     * The table that is targeted.
     * @var string
     */
    protected $table = 'log_death';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'user',
        'killer',
        'weapon',
        'type',
        'money_lost',
        'time',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'killer_id',
        'injury_cause'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * Get the killer
     */
    public function killer()
    {
        return Player::query()->where('name', $this->killer)->first();
    }

    /**
     * Get the player
     */
    public function player()
    {
        return Player::query()->where('name', $this->user)->first();
    }
}
