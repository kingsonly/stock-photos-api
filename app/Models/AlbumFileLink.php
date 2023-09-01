<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlbumFileLink extends Model
{
    use HasFactory;
    public function album(): BelongsTo{
        return $this->belongsTo(Album::class,"album_id");
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class,"user_id");
    }

    public function file(): BelongsTo{
        return $this->belongsTo(StockFiles::class,"file_id");
    }
}
