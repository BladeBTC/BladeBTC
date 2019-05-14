<?php

namespace BladeBTC\GUI\Helpers;

use BladeBTC\GUI\Models\SettingModel;

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