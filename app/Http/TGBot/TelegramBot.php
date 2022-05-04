<?php

namespace App\Http\TGBot;

use App\Models\TelegramUser;

class TelegramBot
{
    public static function check()
    {
        $check = false;

        if (isset($_POST)) {
            $data = file_get_contents("php://input");
            if (json_decode($data) != null) {
                $check = json_decode($data,true);
            }
        }

        return $check;
    }

    public static function checkPayment()
    {
        $array = [];

        if (isset($_POST)) {
            foreach ($_POST as $key => $value) {
                $array[$key] = $value;
            }
        }

        return $array;
    }

    public static function checkQiwi()
    {
        $check = false;

        if (isset($_POST)) {
            $data = file_get_contents("php://input");
            if (json_decode($data) != null) {
                $check = json_decode($data,true);
            }
        }

        return $check;
    }

    public static function replyKeyboard($data)
    {
        return json_encode([
            'keyboard' => $data,
            'resize_keyboard' => true,
            'one_time_keyboard' => false,
            'selective' => true,
        ], true);
    }

    public static function inlineKeyboard($data)
    {
        return json_encode([
            "inline_keyboard" => $data,
        ]);
    }

    public static function removeKeyboard()
    {
        return json_encode([
            "remove_keyboard" => true,
        ]);
    }

    public static function parseRef($data)
    {
        if (stripos($data, '/start') !== false && !empty(trim(explode('/start', $data)[1]))) {
            return trim(explode('/start', $data)[1]);
        }else {
            return false;
        }
    }

    public static function callbackAnnotationSet($callbackId, $text = null)
    {
        if (!$text) $text = 'Ожидайте';

        $data = [
            'callback_query_id' => $callbackId,
            'text' => $text,
        ];

        self::send('answerCallbackQuery', $data);
    }

    public static function sendMessage($chatId, $message, $markup = null, $disableUrlPreview = false)
    {
        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'reply_markup' => $markup,
            'parse_mode' => 'HTML',
        ];

        if ($disableUrlPreview) $data['disable_web_page_preview'] = true;

        return self::send('sendMessage', $data);
    }

    public static function editMessage($chatId, $messageId, $message, $markup = null, $disableUrlPreview = false)
    {
        $data = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $message,
            'reply_markup' => $markup,
            'parse_mode' => 'HTML',
        ];

        if ($disableUrlPreview) $data['disable_web_page_preview'] = true;

        return self::send('editMessageText', $data);
    }

    public static function sendDocument($chatId, $fileName)
    {
        $data = [
            'chat_id' => $chatId,
            'document' => new \CURLFile('lessons/' . $fileName),
        ];

        return self::send('sendDocument', $data);
    }

    public static function sendPhoto($chatId, $photoPath, $caption, $markup = null)
    {
        $data = [
            'chat_id' => $chatId,
            'photo' => new \CURLFile(Storage::path('public/' . $photoPath)),
            'caption' => $caption,
            'reply_markup' => $markup,
        ];

        return self::send('sendPhoto', $data);
    }

    public static function sendVideo($chatId, $videoPath, $width, $height, $caption = null, $keyboard = null)
    {
        $data = [
            'chat_id' => $chatId,
            'video' => new \CURLFile(Storage::path('public/' . $videoPath)),
            'width' => $width,
            'height' => $height,
            'caption' => $caption,
            'reply_markup' => $keyboard,
        ];

        return self::send('sendVideo', $data);
    }

    public static function sendVideoById($chatId, $videoId, $caption = null, $keyboard = null)
    {
        $data = [
            'chat_id' => $chatId,
            'video' => $videoId,
            'caption' => $caption,
            'reply_markup' => $keyboard,
        ];

        return self::send('sendVideo', $data);
    }

    function checkChatMember($chatId, $userId)
    {
        $data = [
            'chat_id' => $chatId,
            'user_id' => $userId,
        ];

        $response = self::send('getChatMember', $data);
        $result = json_decode($response, true);

        if ($result['ok'] == true && $result['result']['status'] !== 'left' && $result['result']['status'] !== 'banned') {
            return true;
        }else {
            return false;
        }
    }

    public static function send($method, $data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,"https://api.telegram.org/bot". Config('telegram.botApiToken') . "/" . $method);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($method == 'sendPhoto') {
            curl_setopt($ch, CURLOPT_HEADER, ['Content-Type:multipart/form-data']);
        }

        $response = curl_exec($ch);

        curl_close ($ch);

        if ($method == 'sendMessage' || $method == 'editMessageText') {
            $dataToInsert = json_decode($response,true);
            $message_id = 0;

            if (!empty($dataToInsert['callback_query'])) {
                $message_id = $dataToInsert['callback_query']['message']['message_id'];
            }else {
                if (empty($dataToInsert['result']['message_id'])) {
                    file_put_contents(app_path('logger.txt'), print_r($dataToInsert, true));
                }else {
                    $message_id = $dataToInsert['result']['message_id'];
                }
            }

            if ($message_id !== 0) TelegramUser::where('uid', $data['chat_id'])->update(['last_message_id' => $message_id]);
        }

        return $response;
    }

    public static function removeEmoji($string) {

        $regex_emoticons = '/[\x{1F600}-\x{1F64F}]/u';
        $clear_string = preg_replace($regex_emoticons, '', $string);

        $regex_symbols = '/[\x{1F300}-\x{1F5FF}]/u';
        $clear_string = preg_replace($regex_symbols, '', $clear_string);

        $regex_transport = '/[\x{1F680}-\x{1F6FF}]/u';
        $clear_string = preg_replace($regex_transport, '', $clear_string);

        $regex_misc = '/[\x{2600}-\x{26FF}]/u';
        $clear_string = preg_replace($regex_misc, '', $clear_string);

        $regex_dingbats = '/[\x{2700}-\x{27BF}]/u';
        $clear_string = preg_replace($regex_dingbats, '', $clear_string);

        return $clear_string;
    }
}
