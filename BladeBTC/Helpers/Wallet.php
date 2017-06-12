<?php

namespace BladeBTC\Helpers;

/**
 * Class Wallet
 * @package BladeBTC\Helpers
 * @see https://blockchain.info/api/blockchain_wallet_api
 */
class Wallet
{
    /**
     * Generate payment address
     *
     * @param $telegram_user_id - ID of the current user requesting address
     * @return string - Payment address
     */
    public static function generateAddress($telegram_user_id)
    {

        /**
         * Database connexion
         */
        $db = Database::get();

        /**
         * Select address from users database if exist
         */
        $wallet_address = $db->query("SELECT investment_address FROM users WHERE telegram_id = '" . $telegram_user_id . "'")->fetchObject()->investment_address;
        if (!is_null($wallet_address) || !empty($wallet_address)) {
            return $wallet_address;
        } else {

            /**
             * Param
             */
            $wallet = getenv("WALLET_ID");
            $main_password = getenv("WALLET_PASSWORD");
            $label = $telegram_user_id;

            /**
             * Request URL
             */
            $json_url = "http://127.0.0.1:3000/merchant/$wallet/new_address?password=$main_password&label=$label";

            /**
             * Request
             */
            $json_data = file_get_contents($json_url);
            $json_feed = json_decode($json_data);


            return $json_feed->address;
        }
    }
}