<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Followers extends Model
{
    use HasFactory;

    public function followersName(){
        return $this->hasOne(User::class,"id","user_follower_id");
    }

    public function userName(){
        return $this->hasOne(User::class,"id","user_id");
    }
}
