<?php

namespace BladeBTC\GUI\Models;

use BladeBTC\GUI\Helpers\Database;
use Exception;
use PDO;

/**
 * Class BotSettingModel
 *
 * @package BladeBTC\GUI\Models
 */
class BotSettingModel
{

    /**
     * Get setting value by name
     *
     * @param $settings
     *
     * @return mixed
     */
    public static function getValueByName($settings)
    {
        $db = Database::get();

        $data = $db->query("SELECT * FROM `bot_setting` WHERE `id` = 1")->fetch(PDO::FETCH_ASSOC);

        return $data[$settings];
    }

    /**
     * Update bot settings
     *
     * @param $data
     *
     * @throws Exception
     */
    public static function update($data)
    {
        $db = Database::get();

        $query = "    UPDATE
                            `bot_setting`
                        SET
                            `app_id` = :app_id,
                            `app_name` = :app_name,
                            `support_chat_id` = :support_chat_id,
                            `wallet_id` = :wallet_id,
                            `wallet_password` = :wallet_password,
                            `wallet_second_password` = :wallet_second_password
                        WHERE
                           id = 1";

        $sth = $db->prepare($query);

        $sth->execute([
            "app_id" => $data["app_id"],
            "app_name" => $data["app_name"],
            "support_chat_id" => $data["support_chat_id"],
            "wallet_id" => $data["wallet_id"],
            "wallet_password" => $data["wallet_password"],
            "wallet_second_password" => $data["wallet_second_password"],
        ]);
    }
}
