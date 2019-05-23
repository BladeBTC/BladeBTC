<?php

namespace BladeBTC\GUI\Controllers;

use BladeBTC\GUI\Helpers\Request;
use BladeBTC\GUI\Models\BotSettingModel;
use Exception;

class ManageBotSetting
{

    /**
     * Edit Bot Settings
     *
     * @return bool
     * @throws Exception
     */
    public static function edit()
    {

        /**
         * Form value
         */
        $app_id = Request::post('app_id');
        $app_name = Request::post('app_name');
        $support_chat_id = Request::post('support_chat_id');
        $wallet_id = Request::post('wallet_id');
        $wallet_password = Request::post('wallet_password');
        $wallet_second_password = Request::post('wallet_second_password');

        /**
         * Validate
         */
        if (empty($app_id)) {
            throw new Exception("You must enter a minimum invest amount.");
        }

        if (empty($app_name)) {
            throw new Exception("You must enter a minimum reinvest amount.");
        }

        if (empty($support_chat_id)) {
            throw new Exception("You must enter a minimum payout amount.");
        }

        if (empty($wallet_id)) {
            throw new Exception("You must enter a base rate percentage.");
        }

        if (empty($wallet_password)) {
            throw new Exception("You must enter a contract time in days.");
        }

        if (empty($wallet_second_password)) {
            throw new Exception("You must enter a commission rate percentage.");
        }

        /**
         * Prepare data
         */
        $setting = [
            "app_id" => $app_id,
            "app_name" => $app_name,
            "support_chat_id" => $support_chat_id,
            "wallet_id" => $wallet_id,
            "wallet_password" => $wallet_password,
            "wallet_second_password" => $wallet_second_password,
        ];

        try {

            BotSettingModel::update($setting);

            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}

