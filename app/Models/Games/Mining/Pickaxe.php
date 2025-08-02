<?php

namespace App\Models\Games\Mining;

use Illuminate\Database\Eloquent\Model;

class Pickaxe extends Model
{
    protected $table = 'pickaxes';

    public function userPickaxes()
    {
        return $this->hasMany(UserPickaxe::class, 'pickaxe_id');
    }
    
}
