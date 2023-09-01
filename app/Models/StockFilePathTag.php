<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockFilePathTag extends Model
{
    use HasFactory;

    public function tag():BelongsTo{
        return $this->belongsTo(Tags::class);
    }

    public function file():BelongsTo{
        return $this->belongsTo(StockFiles::class,"file_id");
    }
}
