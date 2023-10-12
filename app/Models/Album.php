<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class Album extends Model
{
    use HasFactory;
    protected $fillable = [
        'album_name',
        'description',
        'user_id',
    ];
    public function files(): HasMany{
        return $this->hasMany(AlbumFileLink::class,"album_id");
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class,"user_id");
    }
}
