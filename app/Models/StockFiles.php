<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relationships\HasMany;
use Illuminate\Database\Eloquent\Relationships\BelongTo;
class StockFiles extends Model
{
    use HasFactory;
    public function tag():HasMany{
        return $this->hasMany(StockFilePathTag::class,"file_id");
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class,"user_id");
    }
}
