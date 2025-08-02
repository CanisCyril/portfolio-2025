<?php

namespace App\Models\Games\Mining;

use Illuminate\Database\Eloquent\Model;

class UserPickaxe extends Model
{
    protected $table = 'user_pickaxes';
    protected $fillable = ['user_id', 'pickaxe_id', 'equipped'];

    public function pickaxe()
    {
        return $this->belongsTo(Pickaxe::class, );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
