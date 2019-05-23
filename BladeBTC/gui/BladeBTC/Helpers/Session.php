<?php

namespace BladeBTC\GUI\Helpers;


class Session
{

	/**
	 * Display all session value
	 */
	public static function display()
	{
		echo '<pre>';
		print_r($_SESSION);
		echo '</pre>';
	}

    /**
     * Save and return session value
     *
     * @param $key  - Key to create
     * @param $data - Data to save
     *
     * @return mixed|null
     */
	public static function set($key, $data)
	{
		$_SESSION[$key] = $data;

		return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
	}

    /**
     * Get session value
     *
     * @param      $key - Key to get
     *
     * @param null $subkey
     *
     * @return mixed
     */
	public static function get($key, $subkey = null)
	{
		if (!is_null($subkey)) {
			return isset($_SESSION[$key][$subkey]) ? $_SESSION[$key][$subkey] : null;
		}

		return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
	}


    /**
     * Delete session key
     *
     * @param      $key - Key to delete
     * @param null $subkey
     */
	public static function delete($key, $subkey = null)
	{
		if (!is_null($subkey)) {
			unset($_SESSION[$key][$subkey]);
		} else {
			unset($_SESSION[$key]);
		}
	}

    /**
     * Create, save and return form uniqid
     *
     * @param $form_name - Form name
     *
     * @return mixed|null
     */
	public static function setFormId($form_name)
	{
		$_SESSION[$form_name] = uniqid($form_name . "_");

		return isset($_SESSION[$form_name]) ? $_SESSION[$form_name] : null;
	}


	/**
	 * Get orm uniqid
	 *
	 * @param $form_name - Form name
	 *
	 * @return mixed
	 */
	public static function getFormId($form_name)
	{
		return isset($_SESSION[$form_name]) ? $_SESSION[$form_name] : null;
	}

	/**
	 * Destroy current session.
	 */
	public static function kill()
	{
		session_destroy();
	}
}