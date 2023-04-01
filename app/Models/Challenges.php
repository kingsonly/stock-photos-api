<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relationships\HasMany;

class Challenges extends Model
{
    use HasFactory;

    public function entries(){
        return $this->hasMany(ChallengeEntries::class,"challenge_id");
    }
}
