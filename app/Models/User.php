<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Haxneeraj\LaravelVirtualWallet\Interfaces\WalletInterface;
use Haxneeraj\LaravelVirtualWallet\Traits\HasVirtualWallet;
use App\Models\Games\Mining\UserPickaxe;
use App\Models\Games\Mining\UserOre;
use App\Models\Games\Mining\UserMiningLevel;
use App\Models\Games\Mining\UserGold;

class User extends Authenticatable implements WalletInterface
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use HasVirtualWallet;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // public function wallets() {

    //   return $this->hasMany(Wallet::class);
    // }

    public function equippedPickaxe()
    {
        return $this->hasOne(UserPickaxe::class)
            ->where('equipped', true)
            ->with('pickaxe'); // Optional: eager load actual pickaxe model
    }

    public function ores()
    {
        return $this->hasMany(UserOre::class, 'user_id')
            ->with('ore');
    }

    public function level()
    {
        return $this->hasOne(UserMiningLevel::class, 'user_id');
    }

    public function gold()
    {
        return $this->hasOne(UserGold::class, 'user_id');
    }
}
