<?php

if (!function_exists("getCurrentBitcoinRate")) {
    function getCurrentBitcoinRate() {
        $url='https://bitpay.com/api/rates';

        $json = json_decode(
            file_get_contents($url)
        );

        $bitcoinRate = 0;

        foreach($json as $obj){
            if($obj->code == 'EUR')
                $bitcoinRate = $obj->rate;
        }

        return $bitcoinRate;
    }
}

