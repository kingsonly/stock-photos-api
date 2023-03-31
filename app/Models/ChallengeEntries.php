<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
class ChallengeEntries extends Model
{
    use HasFactory;

    public function challenge():BelongsTo {
        return $this->belongsTo(Challenges::class,"challenge_id");
    }

    public function file():BelongsTo {
        return $this->belongsTo(StockFiles::class,"file_id");
    }

    public function user():BelongsTo {
        return $this->belongsTo(User::class,"user_id");
    }
}
