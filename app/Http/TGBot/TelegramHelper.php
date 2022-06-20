<?php

namespace App\Http\TGBot;

use App\Models\Category;
use App\Models\Product;
use App\Models\TelegramUser;
use Illuminate\Support\Facades\Log;

class TelegramHelper
{
    public static function sendOrEditMessage(TelegramUser $user, $message, $keyboard, $disableUrlPreview = true)
    {
        return !empty($user->last_message_id) ?
            TelegramBot::editMessage($user->uid, $user->last_message_id, $message, $keyboard, $disableUrlPreview)
            : TelegramBot::sendMessage($user->uid, $message, $keyboard, $disableUrlPreview);
    }

    public static function getCatalogMainMenu()
    {
        $categories = Category::whereNull('category_id')->get();

        $menu = [];

        foreach ($categories as $category) {
            $menu[] = [
                [
                    'text' => $category->name,
                    'callback_data' => 'get_category_' . $category->id,
                ]
            ];
        }

        $menu[] = [
            [
                'text' => __('button.back'),
                'callback_data' => 'home',
            ]
        ];

        return TelegramBot::inlineKeyboard($menu);
    }

    public static function getSubcategoryMenu($category)
    {
        $menu = [];

        foreach ($category->categories as $categoryItem) {
            $menu[] = [
                [
                    'text' => $categoryItem->name,
                    'callback_data' => 'get_category_' . $categoryItem->id,
                ]
            ];
        }

        $menu[] = [
            [
                'text' => __('button.back'),
                'callback_data' => $category->category_id !== null ? 'get_category_' . $category->category_id : 'catalog',
            ]
        ];

        return TelegramBot::inlineKeyboard($menu);
    }

    public static function getProductsMenuByCategory($category)
    {
        $products = Product::where('category_id', $category->id)->whereNull('user_id')->get();

        $menu = [];

        foreach ($products as $product) {
            $menu[] = [
                [
                    'text' => $product->title . ' | ' . $product->price . ' $',
                    'callback_data' => 'get_product_' . $product->id,
                ]
            ];
        }

        $menu[] = [
            [
                'text' => __('button.back'),
                'callback_data' => $category->category_id !== null ? 'get_category_' . $category->category_id : 'catalog',
            ]
        ];

        return TelegramBot::inlineKeyboard($menu);
    }
}
