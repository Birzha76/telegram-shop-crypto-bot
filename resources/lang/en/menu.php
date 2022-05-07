<?php

return [
    'main' => [
        [__('button.catalog'), __('button.balance')],
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
    ],
    'balance_replenish_menu' => [
        [

            [
                'text' => __('button.cancel'),
                'callback_data' => 'replenish_cancel',
            ],
        ],
    ],
];
