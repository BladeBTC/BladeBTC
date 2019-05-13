<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 2017-10-17
 * Time: 14:59
 */

namespace App\Controllers;

use App\Helpers\Database;
use App\Helpers\Form;
use App\Helpers\Request;
use App\Models\MenuModel;
use Exception;

class ManageMenu
{

	/**
	 * Handle multiples actions in menu management.
	 *
	 * @return null|string
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

				$msg = "Opération terminé.";

				break;


			case "up":

				$current_position = MenuModel::getOrder($menu_id);

				if ($current_position > 1) { //true
					$previous_id = MenuModel::getPrevious($current_position);
					MenuModel::goUp($menu_id, $previous_id, $current_position);
				}

				$msg = "Opération terminé.";

				break;


			case "down":

				$count = MenuModel::getCount();
				$current_position = MenuModel::getOrder($menu_id);

				if ($current_position < $count) {
					$next_id = MenuModel::getNext($current_position);
					MenuModel::goDown($menu_id, $next_id, $current_position);
				}

				$msg = "Opération terminé.";

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
			throw new Exception("Vous devez entrer un titre.");
		}

		/**
		 * Icon validate
		 */
		if ($icon == -1) {
			Form::remove('icon');
			throw new Exception("Vous choisir une icône.");
		}


		/**
		 * Check if MenuModel already have same name.
		 */
		if (!empty($title) && !Database::fieldIsUnique('ae_menu', 'title', $title)) {
			Form::remove('title');
			throw new Exception("Un autre menu porte déjà le même nom.");
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

