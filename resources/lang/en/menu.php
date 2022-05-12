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
                'text' => __('button.back'),
                'callback_data' => 'home',
            ],
        ],
    ],
    'balance_replenish_menu' => [
        [

            [
                'text' => __('button.cancel'),
                'callback_data' => 'balance',
            ],
        ],
    ],
];
