<?php

namespace BladeBTC\GUI\Models;

use BladeBTC\GUI\Helpers\Database;
use Exception;
use PDO;

/**
 * Class settingModel
 *
 * @package App\Models\
 */
class SettingModel
{

	/**
	 * Get setting value by name
	 *
	 * @param $name - Setting Name
	 *
	 * @return mixed
	 */
	public static function getValueByName($name)
	{
		$db = Database::get();

		$data = $db->query("SELECT setting_value FROM gui_setting WHERE setting_name = '$name'")->fetchObject()->setting_value;

		return $data;
	}
}
