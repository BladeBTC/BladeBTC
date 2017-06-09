<?php

namespace BladeBTC\Helpers;

class Wallet
{
    public static function generateAddress($telegram_user_id)
    {

        /**
         * Param
         */
        $wallet = getenv("WALLET_ID");
        $main_password = getenv("WALLET_PASSWORD");
        $label = $telegram_user_id;

        /**
         * Request URL
         */
        $json_url = "http://localhost:3000/merchant/$wallet/new_address?password=$main_password&label=$label";

        /**
         * Request
         */
        $json_data = file_get_contents($json_url);
        $json_feed = json_decode($json_data);


        return $json_feed->address;
    }
}