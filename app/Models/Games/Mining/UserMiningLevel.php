<?php

namespace App\Models\Games\Mining;

use Illuminate\Database\Eloquent\Model;

class UserMiningLevel extends Model
{
    protected $table = 'user_mining_levels';
    protected $fillable = ['user_id', 'total_xp', 'level'];
}
