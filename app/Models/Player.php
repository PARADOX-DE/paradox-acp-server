<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class Player extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;

    /**
     * The table that is targeted.
     * @var string
     */
    protected $table = 'player';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'Name',
        'Pass',
        'Salt',
        'rankId',
        'team',
        'rang',
        'note',
        'warns',
        'timeban',
        'ausschluss',
        'SCName',
        'Offline'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'skin',
        'rp',
        'wanteds',
        'deathnote',
        'angel',
        'angelbackpack',
        'fish1',
        'fish2',
        'fish3',
        'fish4',
        'fish5'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [

    ];

    /**
     * Get the admin rank.
     */
    public function adminRank()
    {
        return AdminRank::query()->where('id', $this->rankId)->first();
    }

    /**
     * Get the admin rank.
     */
    public function faction()
    {
        return Team::query()->where('id', $this->team)->first();
    }

    /**
     * Get the team player rights.
     */
    public function teamPlayerRights()
    {
        return TeamPlayerRights::query()->where('accountid', $this->id)->first();
    }

    /**
     * Get the inventory container
     */
    public function container()
    {
        return ContainerPlayer::query()->where('id', $this->id)->first();
    }

}
