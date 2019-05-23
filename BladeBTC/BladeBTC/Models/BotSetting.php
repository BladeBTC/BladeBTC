<?php

namespace BladeBTC\Models;

use BladeBTC\Helpers\Database;
use PDO;

/**
 * Class BotSettingModel
 *
 * @package BladeBTC\Models
 */
class BotSetting
{

    /**
     * Get setting value by name
     *
     * @param      $settings
     *
     * @return mixed
     */
    public static function getValueByName($settings)
    {
        $db = Database::get();

        $data = $db->query("SELECT * FROM `bot_setting` WHERE `id` = 1")->fetch(PDO::FETCH_ASSOC);

        return $data[$settings];
    }
}
