<?php

namespace App\Models\Games\Mining;

use Illuminate\Database\Eloquent\Model;

class UserGold extends Model
{
    protected $table = 'user_gold';
    protected $fillable = ['user_id', 'amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the current amount of gold for the user.
     *
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }
    
    /**
     * Add gold to the user's balance.
     *
     * @param int $amount
     * @return void
     */
    public function addGold(int $amount): void
    {
        $this->amount += $amount;
        $this->save();
    }

    /**
     * Subtract gold from the user's balance.
     *
     * @param int $amount
     * @return void
     */
    public function subtractGold(int $amount): void
    {
        if ($this->amount >= $amount) {
            $this->amount -= $amount;
            $this->save();
        }
    }
    
    /** 
     * Check if the user has enough gold.
     * @param int $amount
     * @return bool
     */
    public function hasEnoughGold(int $amount): bool
    {
        return $this->amount >= $amount;
    }
}
