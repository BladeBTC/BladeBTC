<?php

namespace BladeBTC\GUI\Helpers;

class Redirect
{
	/**
	 * Redirect user to another page
	 *
	 * @param $page - Page page.php
	 */
	public static function to($page)
	{
		header("Location: " . Path::root() . "/$page");
		exit;
	}

	/**
	 * Redirect to another module
	 *
	 * @param $module_name - Module name
	 */
	public static function module($module_name)
	{
		header("Location: " . Path::root() . "/views/$module_name.php");
		exit;
	}
}