<?php

namespace App\Models\Games\Mining;

use Illuminate\Database\Eloquent\Model;

class UserOre extends Model
{
    protected $table = 'user_ores';
    protected $fillable = ['user_id', 'ore_id', 'amount'];

    public function ore()
    {
        return $this->belongsTo(Ore::class);
    }


}
