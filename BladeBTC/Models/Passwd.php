<?php

namespace BladeBTC\Models;


use BladeBTC\Helpers\Database;

/**
 * Class Passwd
 *
 * @package BladeBTC\Models
 */
class Passwd
{

	/**
	 * Set second password
	 *
	 * @param $password
	 *
	 * @throws \Exception
	 */
	public static function set($password)
	{

		$db = Database::get();

		try {

			$db->beginTransaction();
			$db->query("	INSERT
									INTO
									  `passwd`(
										`passwd`
									  )
									VALUES(
									 " . $db->quote($password) . "
									)");
			$db->commit();
		} catch (\Exception $e) {
			$db->rollBack();
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * Get second password
	 *
	 * @return mixed
	 */
	public static function get()
	{
		$db = Database::get();

		$passwd = $db->query("	SELECT `passwd` FROM `passwd` WHERE `id` = 1")->fetchObject()->passwd;

		return $passwd;
	}
}