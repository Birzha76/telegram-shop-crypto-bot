<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    protected $fillable = [
        'user_id',
        'file_path',
        'status',
    ];

    public function user()
    {
        return $this->hasOne(TelegramUser::class, 'id', 'user_id');
    }
}
