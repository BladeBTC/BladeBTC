<?php

namespace BladeBTC\GUI\Controllers;

use BladeBTC\GUI\Helpers\Database;
use BladeBTC\GUI\Helpers\Form;
use BladeBTC\GUI\Helpers\Request;
use BladeBTC\GUI\Models\MenuModel;
use Exception;

class ManageMenu
{

    /**
     * Handle multiples actions in menu management.
     *
     * @return null|string
     * @throws Exception
     */
	public static function action()
	{

		$menu_id = Request::get('menu_id');
		$action = Request::get('action');

		$msg = null;
		switch ($action) {

			case "delete":

				$count = MenuModel::getCount();
				$position = MenuModel::getOrder($menu_id);

				//Last position
				if ($position == $count) {
					MenuModel::delete($menu_id);
				} //Other than last position
				else {
					MenuModel::delete($menu_id);
					MenuModel::decrement($position);

				}

				$msg = "Done!";

				break;


			case "up":

				$current_position = MenuModel::getOrder($menu_id);

				if ($current_position > 1) { //true
					$previous_id = MenuModel::getPrevious($current_position);
					MenuModel::goUp($menu_id, $previous_id, $current_position);
				}

				$msg = "Done!";

				break;


			case "down":

				$count = MenuModel::getCount();
				$current_position = MenuModel::getOrder($menu_id);

				if ($current_position < $count) {
					$next_id = MenuModel::getNext($current_position);
					MenuModel::goDown($menu_id, $next_id, $current_position);
				}

				$msg = "Done!";

				break;
		}

		return $msg;
	}

	/**
	 * Create new user account
	 *
	 * @return bool
	 * @throws Exception
	 */
	public static function add()
	{


		/**
		 * Save form data
		 */
		Form::save(Request::post());

		/**
		 * Form value
		 */
		$title = Request::post('title');
		$icon = Request::post('icon');

		/**
		 * Title validate
		 */
		if (empty($title)) {
			Form::remove('title');
			throw new Exception("You must enter a title.");
		}

		/**
		 * Icon validate
		 */
		if ($icon == -1) {
			Form::remove('icon');
			throw new Exception("You must choose an icon.");
		}


		/**
		 * Check if MenuModel already have same name.
		 */
		if (!empty($title) && !Database::fieldIsUnique('gui_menu', 'title', $title)) {
			Form::remove('title');
			throw new Exception("Another menu already has the same name.");
		}

		/**
		 * Prepare data
		 */
		$menu = [
			"title"         => $title,
			"icon"          => $icon,
			"display_order" => MenuModel::getCount() + 1,
		];

		try {

			MenuModel::add($menu);

			Form::destroy();

			return true;
		} catch (Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}

