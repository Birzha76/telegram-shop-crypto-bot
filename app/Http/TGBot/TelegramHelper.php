<?php

namespace App\Http\TGBot;

use App\Models\TelegramUser;

class TelegramHelper
{
    public static function sendOrEditMessage(TelegramUser $user, $message, $keyboard, $disableUrlPreview = true)
    {
        return !empty($user->last_message_id) ?
            TelegramBot::editMessage($user->uid, $user->last_message_id, $message, $keyboard, $disableUrlPreview)
            : TelegramBot::sendMessage($user->uid, $message, $keyboard, $disableUrlPreview);
    }
}
