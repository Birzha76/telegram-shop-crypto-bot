<?php

namespace App\Http\Controllers;

use App\Http\Exchanger\Exchanger;
use App\Http\TGBot\TelegramBot;
use App\Models\TelegramUser;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index() {

        return view('admin.home');

    }

    public function hook()
    {
        if (($response = TelegramBot::check()) == false) exit('Hello, world!');

        if (!empty($response['message'])) {
            $uid = $response['message']['from']['id'];
            $message_id = $response['message']['message_id'];
            $first_name = $response['message']['from']['first_name'];
            $userName = $response['message']['from']['username'] ?? null;
            $text = !empty($response['message']['text']) ? $response['message']['text'] : '1';
        }

        if (!empty($response['callback_query'])) {
            $callback_id = $response['callback_query']['id'];
            $callback_data = $response['callback_query']['data'];
            $uid = $response['callback_query']['message']['chat']['id'];
        }

        if (!empty($response['my_chat_member'])) {
            $uid = $response['my_chat_member']['from']['id'];

            if ($response['my_chat_member']['new_chat_member']['status'] == 'kicked') {
                TelegramUser::where('uid', $uid)->update(['ban' => 1]);
            }
            exit();
        }

        if (empty($response['message']) && empty($response['callback_query'])) {
            exit();
        }

        $userInfo = TelegramUser::where('uid', $uid)->first();

        if (!empty($text)) {
            $userRef = TelegramBot::parseRef($text);
        }else {
            $userRef = false;
        }

        if ($userInfo == null) {

            $dataForCreate = [
                'uid' => $uid,
                'username' => $userName,
                'first_name' => $first_name,
                'scene' => 'home',
            ];

            $userInfo = TelegramUser::create($dataForCreate);
        }else {
            if (!empty($userName) && !empty($first_name) && ($userInfo->username !== $userName || $userInfo->first_name !== $first_name)) {
                $userInfo->username = $userName;
                $userInfo->first_name = $first_name;
                $userInfo->save();
            }
        }

        if (!empty($userInfo->ban) && $userInfo->ban == '1') {
            TelegramUser::where('uid', $uid)->update(['ban' => 0]);
        }

        $keyboard = null;

        // Обрабатываем колбэки

        if (!empty($response['callback_query'])) {
            switch ($callback_data) {
                case 'replenish_btc':
                    $answer = __('answer.replenish_btc');
                    $keyboard = TelegramBot::inlineKeyboard(__('menu.balance_replenish_menu'));

                    $message_id = $userInfo->last_message_id;

                    if (!empty($message_id)) {
                        TelegramBot::editMessage($uid, $message_id, $answer, $keyboard);
                    } else {
                        TelegramBot::sendMessage($uid, $answer, $keyboard);
                    }
                    break;
                case 'replenish_ltc':
                    $answer = __('answer.replenish_ltc');
                    $keyboard = TelegramBot::inlineKeyboard(__('menu.balance_replenish_menu'));

                    $message_id = $userInfo->last_message_id;

                    if (!empty($message_id)) {
                        TelegramBot::editMessage($uid, $message_id, $answer, $keyboard);
                    } else {
                        TelegramBot::sendMessage($uid, $answer, $keyboard);
                    }
                    break;
                case 'replenish_cancel':
                    $exchangeRateBtc = Exchanger::getCryptoCurrency('BTC');
                    $exchangeRateLtc = Exchanger::getCryptoCurrency('LTC');

                    $answer = str_replace([':exchange_rate_btc:', ':exchange_rate_ltc:'], [$exchangeRateBtc, $exchangeRateLtc], __('answer.balance_main'));
                    $keyboard = TelegramBot::inlineKeyboard(__('menu.balance_main_menu'));

                    $message_id = $userInfo->last_message_id;

                    if (!empty($message_id)) {
                        TelegramBot::editMessage($uid, $message_id, $answer, $keyboard);
                    } else {
                        TelegramBot::sendMessage($uid, $answer, $keyboard);
                    }
                    break;

//                case (stripos($callback_data, 'buy_product') !== false):
//                    $productId = explode('_', $callback_data)[2];
//
//                    $product = Product::find($productId);
//
//                    $message = 'Новый заказ
//Пользователь: ' . $userInfo->first_name . ' (' . $uid . ')
//Продукт: ' . $product->title . '
//Цена: ' . $product->price;
//
//                    $responseAfterPublishingInChannel = TelegramBot::sendMessage(
//                        Config('telegram.adminChannelId'),
//                        $message,
//                    );
//                    $responseAfterPublishingInChannel = json_decode($responseAfterPublishingInChannel, true);
//
//                    Order::create([
//                        'telegram_user_id' => $userInfo->id,
//                        'product_id' => $productId,
//                        'message_id' => $responseAfterPublishingInChannel['result']['message_id'],
//                    ]);
//
//                    $answer = __('answer.manager_waiting');
//
//                    $message_id = $userInfo->last_message_id;
//
//                    if (!empty($message_id)) {
//                        TelegramBot::editMessage($uid, $message_id, $answer, $keyboard);
//                    }else {
//                        TelegramBot::sendMessage($uid, $answer, $keyboard);
//                    }
//                    break;
            }
        }

        // Конец обработки колбэков

        // Обрабатываем обычные сообщения

        if (!empty($response['message'])) {
            switch ($text) {
                case __('button.cabinet'):
                    $answer = str_replace([':balance:'], [$userInfo->balance], __('answer.cabinet'));
                    $keyboard = TelegramBot::replyKeyboard(__('menu.main'));

                    TelegramBot::sendMessage($uid, $answer, $keyboard);
                    break;
                case __('button.balance'):
                    $exchangeRateBtc = Exchanger::getCryptoCurrency('BTC');
                    $exchangeRateLtc = Exchanger::getCryptoCurrency('LTC');

                    $answer = str_replace([':exchange_rate_btc:', ':exchange_rate_ltc:'], [$exchangeRateBtc, $exchangeRateLtc], __('answer.balance_main'));
                    $keyboard = TelegramBot::inlineKeyboard(__('menu.balance_main_menu'));

                    TelegramBot::sendMessage($uid, $answer, $keyboard);
                    break;
//                case (in_array($text, $allServices)):
//                    $serviceInfo = Service::where('name', $text)->first();
//
//                    $answer = str_replace([':service:'], [$text], __('answer.service'));
//                    $keyboard = TelegramHelper::makeServiceInlineKeyboard($serviceInfo->id);
//
//                    TelegramBot::sendMessage($uid, $answer, $keyboard);
//                    break;
                default:
                    $answer = __('answer.start');
                    $keyboard = TelegramBot::replyKeyboard(__('menu.main'));

                    TelegramBot::sendMessage($uid, $answer, $keyboard);
            }
        }

        // Конец обработки обычных сообщений
    }

    public function fix()
    {
        //
    }
}
