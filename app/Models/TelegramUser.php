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
        'scene',
        'last_message_id',
        'ban',
    ];
}
