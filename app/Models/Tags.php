<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tags extends Model
{
    use HasFactory;
    
    public function file():HasMany
    {
        return $this->hasMany(StockFilePathTag::class,"tag_id");
    }

    public function tags():HasMany
    {
        return $this->hasMany(Tags::class, 'creator_id');
    }

    protected $hidden = [
        'updated_at'
    ];

    protected $fillable = [
        'name',
        'creator_id',
    ];
}
