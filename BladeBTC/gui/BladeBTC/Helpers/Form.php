<?php

namespace BladeBTC\GUI\Helpers;

/**
 * Class Form
 *
 * @package App\Helpers
 */
class Form
{

	/**
	 * @return mixed - Prepared form ID
	 */
	private static function generateFormId()
	{
		$module = $_SERVER["SCRIPT_NAME"];
		$module = pathinfo($module)['filename'];

		return $module;
	}

	/**
	 * Save form data content
	 *
	 * @param      $data      - Data
	 * @param bool $edit_mode - If true save form data and add key edit_mode = 1
	 *
	 * @internal param $form_id - Unique form id
	 */
	public static function save($data, $edit_mode = false)
	{
		if ($edit_mode) {
			$data = Utils::array_push_assoc($data, "edit_mode", 1);
			Session::set(self::generateFormId(), $data);
		} else {
			Session::set(self::generateFormId(), $data);
		}
	}

	/**
	 * Update current Form data with new form data.
	 *
	 * @param $data - Data
	 *
	 * @internal param $form_id - Form Id
	 */
	public static function update($data)
	{
		$old = Session::get(self::generateFormId());
		$new = $data;
		$final = array_merge($old, $new);
		Session::set(self::generateFormId(), $final);
	}

	/**
	 * Get echo form data field
	 *
	 * @param $field - Field to get
	 *
	 * @internal param $form_id - Unique form id
	 */
	public static function get($field)
	{
		echo Session::get(self::generateFormId(), $field);
	}

	/**
	 * Get return form data field
	 *
	 * @param $field - Field to get
	 *
	 * @return mixed
	 * @internal param $form_id - Unique form id
	 */
	public static function getReturn($field)
	{
		return Session::get(self::generateFormId(), $field);
	}


	/**
	 * Delete form data field
	 *
	 * @param $field - Field to get
	 *
	 * @internal param $form_id - Unique form id
	 */
	public static function remove($field)
	{
		Session::delete(self::generateFormId(), $field);
	}

	/**
	 * Delete all form data
	 *
	 * @internal param $form_id - Unique form id
	 */
	public static function destroy()
	{
		Session::delete(self::generateFormId());
	}
}