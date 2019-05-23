<?php

namespace BladeBTC\GUI\Helpers;

use EmailValidator\Validator as EmailValidator;

class Utils
{

	/**
	 * This function act as print_r but with nice display.
	 *
	 * @param $object - $data object
	 */
	public static function print_r2($object)
	{
		echo "<pre>";
		print_r($object);
		echo "</pre>";
	}

	/**
	 * Format phone number 450-456-4565
	 *
	 * @param $number - Input number
	 *
	 * @return mixed - Output number
	 */
	public static function pnf($number)
	{
		return preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $number);
	}

    /**
     * Format number
     *
     * @param      $float
     * @param int  $precision
     * @param bool $no_space
     *
     * @return double
     */
	public static function nf($float, $precision = 2, $no_space = false)
	{
		if ($no_space) {
			return number_format(round($float, $precision, PHP_ROUND_HALF_EVEN), $precision, '.', '');
		}

		return number_format(round($float, $precision, PHP_ROUND_HALF_EVEN), $precision, '.', ' ');
	}

	/**
	 * Format Date
	 *
	 * @param $format
	 * @param $timestamp
	 *
	 * @return false|string
	 */
	public static function dateFromTimeStamp($format, $timestamp)
	{
		if (!empty($timestamp)) {
			return date($format, strtotime($timestamp));
		}

		return '';
	}

	/**
	 * Get user IP address
	 *
	 * @return mixed - IP
	 */
	public static function getIP()
	{
		$ip = $_SERVER['REMOTE_ADDR'];

		return $ip;
	}

	/**
	 * Push to associative array
	 *
	 * @param $array
	 * @param $key
	 * @param $value
	 *
	 * @return mixed
	 */
	public static function array_push_assoc($array, $key, $value)
	{
		$array[$key] = $value;

		return $array;
	}

	/**
	 * put link active
	 *
	 * @param $page - Page name
	 *
	 * @return bool
	 */
	public static function active($page)
	{
		$current_page = basename($_SERVER['PHP_SELF']);
		if ($current_page == $page) {
			echo 'class="active"';

			return true;
		}

		return false;
	}

    /**
     * Partially validate email
     * (Only validate email format)
     *
     * @param string $email - Email
     *
     * @return bool
     */
	public static function isEmail($email)
	{

		$validator = new EmailValidator();

		return $validator->isEmail($email);
	}

	/**
	 * Remove http:// or https://
	 *
	 * @param $str
	 *
	 * @return mixed
	 */
	public static function strip_http($str)
	{
		$str = preg_replace('#^https?://#', '', $str);

		return $str;
	}
}