<?php

namespace BladeBTC\GUI\Helpers;


class Password
{

	/**
	 * Validate password
	 *
	 * @param $password - Text password
	 * @param $hash     - Stored Hash
	 *
	 * @return bool
	 */
	public static function isValid($password, $hash)
	{
		if (password_verify($password, $hash)) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * validate if hash required update
	 *
	 * @param $password
	 * @param $hash
	 *
	 * @return bool|string
	 */
	public static function needsRehash($password, $hash)
	{
		$options = [
			'cost' => 10,
		];

		if (password_needs_rehash($hash, PASSWORD_BCRYPT, $options)) {

			return self::hash($password);
		}

		return false;
	}

	/**
	 * Hash password
	 *
	 * @param $password - Text password
	 *
	 * @return string
	 */
	public static function hash($password)
	{
		$options = [
			'cost' => 10,
		];

		return password_hash($password, PASSWORD_BCRYPT, $options);
	}

	/**
	 * Get best cost for the current serveur
	 */
	public static function getBestCost()
	{
		$timeTarget = 0.05;

		$cost = 8;
		do {
			$cost++;
			$start = microtime(true);
			password_hash("test", PASSWORD_BCRYPT, ["cost" => $cost]);
			$end = microtime(true);
		} while (($end - $start) < $timeTarget);

		echo "Best cost for this server is : " . $cost . "\n";
	}
}