<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 2017-11-08
 * Time: 11:38
 */

namespace App\Helpers;

use App\Models\SettingModel;

class Setting
{
	/**
	 * Return Setting Value
	 *
	 * @return string
	 */
	public static function get($setting_name)
	{
		return SettingModel::getValueByName($setting_name);
	}
}