<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

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


    protected $fillable = [
        'name',
        'creator_id',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('creator', function (Builder $builder){
            $builder->where('creator_id', Auth::id());
        });
    }
}
