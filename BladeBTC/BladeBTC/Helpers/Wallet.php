<?php

namespace BladeBTC\Helpers;

use BladeBTC\Models\BotSetting;
use BladeBTC\Models\InvestmentPlan;
use stdClass;

/**
 * Class Wallet
 *
 * @package BladeBTC\Helpers
 * @see     https://blockchain.info/api/blockchain_wallet_api
 */
class Wallet
{
    /**
     * Generate payment address
     *
     * @param $telegram_user_id - ID of the current user requesting address
     *
     * @return object - Payment address
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
        $wallet_address = $db->query("SELECT `investment_address` FROM `users` WHERE `telegram_id` = '$telegram_user_id'")->fetchObject()->investment_address;
        if (!is_null($wallet_address) || !empty($wallet_address)) {
            $data = new stdClass();
            $data->address = $wallet_address;
        }
        else {

            /**
             * Param
             */
            $wallet = BotSetting::getValueByName("wallet_id");
            $main_password = BotSetting::getValueByName("wallet_password");
            $second_password = BotSetting::getValueByName("wallet_second_password");
            $label = $telegram_user_id;

            /**
             * Request URL
             */
            $url = "http://127.0.0.1:3000/merchant/$wallet/new_address?password=$main_password&second_password=$second_password&label=$label";

            /**
             * Request
             */
            $data = Curl::get($url);

        }

        return $data;
    }


    /**
     * Get wallet balance
     *
     * @return mixed
     */
    public static function getWalletBalance()
    {

        /**
         * Param
         */
        $wallet = BotSetting::getValueByName("wallet_id");
        $main_password = BotSetting::getValueByName("wallet_password");
        $second_password = BotSetting::getValueByName("wallet_second_password");

        /**
         * Request URL
         */
        $url = "http://127.0.0.1:3000/merchant/$wallet/balance?password=$main_password&second_password=$second_password";

        /**
         * Request
         */
        $data = Curl::get($url);

        return $data->balance;
    }

    /**
     * Send bitcoin to a specific address
     *
     * @param $to_wallet_address - Wallet address
     * @param $satoshi_amount    - Satoshi amount
     *
     * @return object - Message
     */
    public static function makeOutgoingPayment($to_wallet_address, $satoshi_amount)
    {
        /**
         * Param
         */
        $wallet = BotSetting::getValueByName("wallet_id");
        $main_password = BotSetting::getValueByName("wallet_password");
        $second_password = BotSetting::getValueByName("wallet_second_password");
        $fee = InvestmentPlan::getValueByName("withdraw_fee");

        /**
         * Removing transaction fee
         */
        $send_amount_without_fee = $satoshi_amount - $fee;

        /**
         * Request URL
         */
        $url = "http://127.0.0.1:3000/merchant/$wallet/payment?password=$main_password&second_password=$second_password&to=$to_wallet_address&amount=$send_amount_without_fee&fee=$fee";

        $data = Curl::get($url);

        return $data;
    }


    /**
     * List address
     *
     * @return mixed
     * @see    https://blockchain.info/q/getblockcount
     */
    public static function listAddress()
    {
        /**
         * Param
         */
        $wallet = BotSetting::getValueByName("wallet_id");
        $main_password = BotSetting::getValueByName("wallet_password");
        $second_password = BotSetting::getValueByName("wallet_second_password");

        /**
         * Request URL
         */
        $url = "http://127.0.0.1:3000/merchant/$wallet/list?password=$main_password&second_password=$second_password";

        /**
         * Request
         */
        $data = Curl::get($url, true);

        return $data;
    }

    /**
     * Get the amount received and confirmed for an address
     *
     * @param $address
     *
     * @return bool|string
     */
    public static function getConfirmedReceivedByAddress($address)
    {
        $url = "https://blockchain.info/q/getreceivedbyaddress/$address?confirmations=" . InvestmentPlan::getValueByName("required_confirmations");

        $data = Curl::getRaw($url);

        return $data;
    }
}