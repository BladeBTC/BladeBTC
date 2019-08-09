<?php

namespace BladeBTC\GUI\Helpers;


class Request
{
	/**
	 * Get post value
	 *
	 * @param null $key - Array key
	 *
	 * @return mixed
	 */
	public static function post($key = null)
	{
		if (is_null($key)) {
			return isset($_POST) ? $_POST : null;
		} else {
			return isset($_POST[$key]) ? $_POST[$key] : null;
		}
	}

	/**
	 * Get get value
	 *
	 * @param null $key - Array Key
	 *
	 * @return mixed
	 */
	public static function get($key = null)
	{
		if (is_null($key)) {
			return isset($_GET) ? $_GET : null;
		} else {
			return isset($_GET[$key]) ? $_GET[$key] : null;
		}
	}


	/**
	 * Get file value
	 *
	 * @param null $key - Array Key
	 *
	 * @return mixed
	 */
	public static function file($key = null)
	{
		if (is_null($key)) {
			return isset($_FILES) ? $_FILES : null;
		} else {
			return isset($_FILES[$key]) ? $_FILES[$key] : null;
		}
	}
}