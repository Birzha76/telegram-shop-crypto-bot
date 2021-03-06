<?php

return [
    'main' => [
        [__('button.catalog'), __('button.balance')],
    ],
    'main_menu' => [
        [

            [
                'text' => __('button.catalog'),
                'callback_data' => 'catalog',
            ],
            [
                'text' => __('button.balance'),
                'callback_data' => 'balance',
            ],
        ],
        [

            [
                'text' => __('button.purchases'),
                'callback_data' => 'purchases',
            ],
        ],
    ],
    'your_item_menu' => [
        [

            [
                'text' => __('button.back'),
                'callback_data' => 'home',
            ],
        ],
    ],
    'your_purchases_details' => [
        [

            [
                'text' => __('button.back'),
                'callback_data' => 'purchases',
            ],
        ],
    ],
    'balance_main_menu' => [
        [

            [
                'text' => __('button.replenish_btc'),
                'callback_data' => 'replenish_btc',
            ],
            [
                'text' => __('button.replenish_ltc'),
                'callback_data' => 'replenish_ltc',
            ],
        ],
        [

            [
                'text' => __('button.cash_app'),
                'callback_data' => 'cash_app',
            ],
        ],
        [

            [
                'text' => __('button.back'),
                'callback_data' => 'home',
            ],
        ],
    ],
    'balance_replenish_menu' => [
        [

            [
                'text' => __('button.send_check'),
                'callback_data' => 'send_check',
            ],
        ],
        [

            [
                'text' => __('button.cancel'),
                'callback_data' => 'balance',
            ],
        ],
    ],
    'balance_send_check_menu' => [
        [

            [
                'text' => __('button.cancel'),
                'callback_data' => 'balance',
            ],
        ],
    ],
];
