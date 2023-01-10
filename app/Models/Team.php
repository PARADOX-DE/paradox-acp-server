<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Team extends Model
{
    public $timestamps = false;

    /**
     * The table that is targeted.
     * @var string
     */
    protected $table = 'team';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'name_short',
        'color',
        'rgb',
        'note',
        'warns',
        'isActive',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'lastbankrob',
        'LastOutfitPreQuest',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * Get the players.
     */
    public function players()
    {
        return Player::query()->where('team', $this->id);
    }

    /**
     * Get the shelter.
     */
    public function shelter()
    {
        return TeamShelter::query()->where('teamid', $this->id)->first();
    }

    /**
     * Get the ammo armory.
     */
    public function ammoArmory()
    {
        return TeamAmmoArmory::query()->where('team_id', $this->id)->first();
    }
}
