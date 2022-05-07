<?php

namespace App\Http\Exchanger;

use Illuminate\Support\Facades\Http;

class Exchanger
{
    public static function getCryptoCurrency($coin, $type = 'a')
    {
        $json = Http::get("https://api.kraken.com/0/public/Ticker?pair=" . $coin . "USD");
        $obj = $json->json();

        switch ($type)
        {
            case ($type == 'a' && $coin == 'BTC'):
                $result = ceil($obj['result']['XXBTZUSD']['a'][0]) ?? null;
                break;
            case ($type == 'b' && $coin == 'BTC'):
                $result = ceil($obj['result']['XXBTZUSD']['b'][0]) ?? null;
                break;
            case ($type == 'a' && $coin == 'LTC'):
                $result = ceil($obj['result']['XLTCZUSD']['a'][0]) ?? null;
                break;
            case ($type == 'b' && $coin == 'LTC'):
                $result = ceil($obj['result']['XLTCZUSD']['b'][0]) ?? null;
                break;
            default:
                $result = null;
        }

        return $result;
    }
}
