<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
    protected $fillable = [
        'uid',
        'parent_uid',
        'username',
        'first_name',
        'balance',
        'balance_btc',
        'balance_ltc',
        'scene',
        'last_message_id',
        'ban',
    ];

    public function purchases()
    {
        return $this->hasMany(Product::class, 'user_id', 'id');
    }
}
