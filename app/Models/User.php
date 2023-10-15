<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mpociot\Reflection\DocBlock\Tag;
use App\Models\Tags;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function album():HasMany
    {
        return $this->hasMany(Album::class,"user_id");
    }

    public function tags():HasMany
    {
        return $this->hasMany(Tags::class, 'creator_id');
    }

    public function files():HasMany{
        return $this->hasMany(StockFiles::class,"user_id");
    }

    public function challengeEntry():HasMany{
        return $this->hasMany(ChallengeEntries::class,"user_id");
    }
}
