<?php

namespace App\Http\Controllers;

use App\Enums\CheckStatus;
use App\Http\Exchanger\Exchanger;
use App\Http\TGBot\TelegramBot;
use App\Http\TGBot\TelegramHelper;
use App\Models\Category;
use App\Models\Check;
use App\Models\Product;
use App\Models\Setting;
use App\Models\TelegramUser;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
                case 'home':
                    $answer = __('answer.start');
                    $keyboard = TelegramBot::inlineKeyboard(__('menu.main_menu'));

                    TelegramHelper::sendOrEditMessage($userInfo, $answer, $keyboard);

                    if ($userInfo->scene !== 'home') {
                        $userInfo->scene = 'home';
                        $userInfo->save();
                    }
                    break;
                case 'catalog':
                    $answer = __('answer.catalog_main');
                    $keyboard = TelegramHelper::getCatalogMainMenu();

                    TelegramHelper::sendOrEditMessage($userInfo, $answer, $keyboard);
                    break;
                case (stripos($callback_data, 'get_category') !== false):
                    $categoryId = explode('get_category_', $callback_data)[1];

                    $category = Category::find($categoryId);

                    if ($category->categories->count() > 0) {
                        $answer = __('answer.catalog_main') . '

' . $category->name;
                        $keyboard = TelegramHelper::getSubcategoryMenu($category);
                    }else {
                        $answer = __('answer.catalog_main') . '

' . $category->name;
                        $keyboard = TelegramHelper::getProductsMenuByCategory($category);
                    }

                    TelegramHelper::sendOrEditMessage($userInfo, $answer, $keyboard);
                    break;
                case (stripos($callback_data, 'get_product') !== false):
                    $productId = explode('get_product_', $callback_data)[1];

                    $product = Product::find($productId);

                    $answer = $product->title . '

Price: ' . $product->price . ' $

' . $product->description;

                    $arrayOfKeyboard = [
                        [

                            [
                                'text' => __('button.buy_with_btc'),
                                'callback_data' => 'buy_product_btc_' . $productId,
                            ],
                            [
                                'text' => __('button.buy_with_ltc'),
                                'callback_data' => 'buy_product_ltc_' . $productId,
                            ],
                        ],
                        [

                            [
                                'text' => __('button.back'),
                                'callback_data' => 'get_category_' . $product->category_id,
                            ],
                        ],
                        [

                            [
                                'text' => __('button.to_main_menu'),
                                'callback_data' => 'home',
                            ],
                        ],
                    ];
                    $keyboard = TelegramBot::inlineKeyboard($arrayOfKeyboard);

                    TelegramHelper::sendOrEditMessage($userInfo, $answer, $keyboard);
                    break;
                case (stripos($callback_data, 'buy_product') !== false):
                    $productId = explode('_', $callback_data)[3];
                    $paymentMethod = explode('_', $callback_data)[2];

                    $product = Product::find($productId);

                    if (!$product) {
                        TelegramBot::callbackAnnotationSet($callback_id, __('answer.already_bought'));
                        exit();
                    }

                    if ($paymentMethod == 'ltc') {
                        $exchangeRate = Exchanger::getCryptoCurrency('LTC');
                    }else {
                        $exchangeRate = Exchanger::getCryptoCurrency('BTC');
                    }

                    $productPriceInCrypto = round($product->price / $exchangeRate, 5);

                    if ($paymentMethod == 'ltc') {
                        if ($userInfo->balance_ltc < $productPriceInCrypto) {
                            TelegramBot::callbackAnnotationSet($callback_id, __('answer.no_money'));
                            exit();
                        }

                        $userInfo->balance_ltc -= $productPriceInCrypto;
                        $userInfo->save();
                    } else {
                        if ($userInfo->balance_btc < $productPriceInCrypto) {
                            TelegramBot::callbackAnnotationSet($callback_id, __('answer.no_money'));
                            exit();
                        }

                        $userInfo->balance_btc -= $productPriceInCrypto;
                        $userInfo->save();
                    }

                    $answer = str_replace([
                        ':details:',
                    ], [
                        $product->details,
                    ], __('answer.your_item'));

                    $keyboard = TelegramBot::inlineKeyboard(__('menu.your_item_menu'));

                    TelegramHelper::sendOrEditMessage($userInfo, $answer, $keyboard);

                    $product->delete();
                    break;
                case 'balance':
                    $exchangeRateBtc = Exchanger::getCryptoCurrency('BTC');
                    $exchangeRateLtc = Exchanger::getCryptoCurrency('LTC');

                    $answer = str_replace([
                        ':exchange_rate_btc:',
                        ':exchange_rate_ltc:',
                        ':balance_btc:',
                        ':balance_btc_in_usd:',
                        ':balance_ltc:',
                        ':balance_ltc_in_usd:'
                    ], [
                        $exchangeRateBtc,
                        $exchangeRateLtc,
                        $userInfo->balance_btc,
                        round($userInfo->balance_btc * $exchangeRateBtc),
                        $userInfo->balance_ltc,
                        round($userInfo->balance_ltc * $exchangeRateLtc),
                    ], __('answer.balance_main'));
                    $keyboard = TelegramBot::inlineKeyboard(__('menu.balance_main_menu'));

                    TelegramHelper::sendOrEditMessage($userInfo, $answer, $keyboard);

                    if ($userInfo->scene !== 'home') {
                        $userInfo->scene = 'home';
                        $userInfo->save();
                    }
                    break;
                case 'replenish_btc':
                    $answer = str_replace([
                        ':wallet_btc:',
                    ], [
                        Setting::where('param', 'wallet_btc')->first()->content,
                    ], __('answer.replenish_btc'));
                    $keyboard = TelegramBot::inlineKeyboard(__('menu.balance_replenish_menu'));

                    TelegramHelper::sendOrEditMessage($userInfo, $answer, $keyboard);
                    break;
                case 'replenish_ltc':
                    $answer = str_replace([
                        ':wallet_ltc:',
                    ], [
                        Setting::where('param', 'wallet_ltc')->first()->content,
                    ], __('answer.replenish_ltc'));
                    $keyboard = TelegramBot::inlineKeyboard(__('menu.balance_replenish_menu'));

                    TelegramHelper::sendOrEditMessage($userInfo, $answer, $keyboard);
                    break;
                case 'cash_app':
                    $answer = str_replace([
                        ':cash_img:',
                        ':cash_text:',
                    ], [
                        asset('storage/' . Setting::where('param', 'cash_img')->first()->content),
                        Setting::where('param', 'cash_text')->first()->content,
                    ], __('answer.cash_app'));
                    $keyboard = TelegramBot::inlineKeyboard(__('menu.balance_replenish_menu'));

                    TelegramHelper::sendOrEditMessage($userInfo, $answer, $keyboard, false);
                    break;
                case 'send_check':
                    $answer = __('answer.send_check');
                    $keyboard = TelegramBot::inlineKeyboard(__('menu.balance_send_check_menu'));

                    TelegramHelper::sendOrEditMessage($userInfo, $answer, $keyboard);

                    $userInfo->scene = 'send_check';
                    $userInfo->save();
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
                case ($userInfo->scene == 'send_check'):
                    TelegramBot::deleteMessage($uid, $message_id);

                    Log::channel('telegram')->info($response);

                    if (empty($response['message']['document'])) {
                        $answer = __('answer.invalid_check');
                        $keyboard = TelegramBot::inlineKeyboard(__('menu.balance_send_check_menu'));

                        TelegramHelper::sendOrEditMessage($userInfo, $answer, $keyboard);
                        exit();
                    }

                    $fileId = $response['message']['document']['file_id'];
                    $fileName = $response['message']['document']['file_name'];
                    $fileLength = $response['message']['document']['file_size'];
                    $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

                    if ($fileLength > (1000000 * 20)) {
                        $answer = __('answer.invalid_check_size');
                        $keyboard = TelegramBot::inlineKeyboard(__('menu.balance_send_check_menu'));

                        TelegramHelper::sendOrEditMessage($userInfo, $answer, $keyboard);
                        exit();
                    }

                    $checkFileContent = TelegramBot::getFileById($fileId);
                    $pathForSave = 'public/checks/' . Str::uuid() . '.' . $fileExtension;

                    Storage::put($pathForSave, $checkFileContent);

                    Check::create([
                        'user_id' => $userInfo->id,
                        'file_path' => $pathForSave,
                        'status' => CheckStatus::UnderConsideration,
                    ]);

                    $answer = __('answer.check_send_success');
                    $keyboard = TelegramBot::inlineKeyboard(__('menu.main_menu'));

                    TelegramHelper::sendOrEditMessage($userInfo, $answer, $keyboard);

                    $userInfo->scene = 'home';
                    $userInfo->save();
                    break;
                default:
                    $answer = __('answer.start');
                    $keyboard = TelegramBot::inlineKeyboard(__('menu.main_menu'));

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
