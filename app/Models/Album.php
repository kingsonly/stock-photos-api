<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
class Album extends Model
{
    use HasFactory;
    public function files(): HasMany{
        return $this->hasMany(AlbumFileLink::class,"album_id");
    }

    public function user(): HasOne{
        return $this->belongsTo(User::class,"user_id");
    }
}
