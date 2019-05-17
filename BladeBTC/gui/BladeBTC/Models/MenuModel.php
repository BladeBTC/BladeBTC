<?php

namespace BladeBTC\GUI\Models;

use BladeBTC\GUI\Helpers\Database;
use Exception;
use PDOStatement;

/**
 * Class MenuModel
 *
 * @package App\Models
 */
class MenuModel
{
	/**
	 * Get all menu elements
	 *
	 * @return PDOStatement
	 */
	public static function getAll()
	{
		$db = Database::get();

		$menu = $db->query("SELECT * FROM gui_menu ORDER BY display_order");

		return $menu;
	}


	/**
	 * Get menu name
	 *
	 * @param $menu_id - menu id
	 *
	 * @return mixed
	 */
	public static function getNameById($menu_id)
	{
		$db = Database::get();

		$name = $db->query("SELECT title FROM gui_menu WHERE menu_id=$menu_id")->fetchObject()->title;

		return $name;
	}

	/**
	 * Get childs elements of a menu
	 *
	 * @param $menu_id  - Parent ID
	 * @param $group_id - Group ID
	 *
	 * @return array|null
	 */
	public static function getChilds($menu_id, $group_id)
	{
		$db = Database::get();

		$final_modules = null;
		$modules = $db->query("SELECT * FROM gui_module WHERE parent = $menu_id AND active = 1 AND static = 0");
		while ($module = $modules->fetchObject()) {

			$groups = explode(";", $module->access_level);

			if (in_array($group_id, $groups)) {
				$final_modules[] = $module;
			}
		}

		return $final_modules;
	}

    /**
     * Count childs items
     *
     * @param $menu_id
     *
     * @param $group_id
     *
     * @return mixed
     */
	public static function getChildsCount($menu_id, $group_id)
	{
		$db = Database::get();

		$count = 0;
		$modules = $db->query("SELECT *  FROM gui_module WHERE parent = $menu_id AND active = 1 AND static = 0");
		while ($module = $modules->fetchObject()) {

			$groups = explode(";", $module->access_level);

			if (in_array($group_id, $groups)) {
				$count++;
			}
		}

		return $count;
	}


	/**
	 * Get display order
	 *
	 * @param $menu_id - MenuModel id
	 *
	 * @return mixed
	 */
	public static function getOrder($menu_id)
	{
		$db = Database::get();

		$order = $db->query("SELECT display_order FROM gui_menu WHERE menu_id = $menu_id")->fetchObject()->display_order;

		return $order;
	}


	/**
	 * Count items
	 *
	 * @return mixed
	 */
	public static function getCount()
	{
		$db = Database::get();

		$count = $db->query("SELECT COUNT(*) AS C FROM gui_menu")->fetchObject()->C;

		return $count;
	}

	/**
	 * Get next menu Id
	 *
	 * @return mixed
	 */
	public static function getNextMenuId()
	{
		$db = Database::get();

		$next = $db->query("SELECT MAX(menu_id) AS C FROM gui_menu")->fetchObject()->C;

		return $next + 1;
	}

    /**
     * Create AccountModel
     *
     * @param $data - AccountModel Data
     *
     * @throws Exception
     */
	public static function add($data)
	{
		$db = Database::get();

		$query = "	INSERT INTO 
					  gui_menu (
					  	menu_id,
						title, 
						icon, 
						display_order 
					  )
					VALUES (
						:menu_id,
						:title, 
						:icon, 
						:display_order 
					)";

		$sth = $db->prepare($query);

		$sth->execute([
			"menu_id"       => self::getNextMenuId(),
			"title"         => $data["title"],
			"icon"          => $data["icon"],
			"display_order" => $data["display_order"],
		]);
	}

    /**
     * Delete menu
     *
     * @param $menu_id - MenuModel ID
     *
     * @throws Exception
     */
	public static function delete($menu_id)
	{

		$db = Database::get();

		$query = "	DELETE FROM 
						gui_menu 
					WHERE 
						menu_id = :menu_id";

		$sth = $db->prepare($query);

		$sth->execute([
			"menu_id" => $menu_id,
		]);

	}


	/**
	 * Decrement all display order from start position
	 *
	 * @param $start_position - Decrement under this position
	 */
	public static function decrement($start_position)
	{

		$db = Database::get();

		$updates = $db->query("SELECT * FROM gui_menu WHERE display_order > $start_position");

		while ($update = $updates->fetchObject()) {
			$db->query("UPDATE 
									gui_menu 
								  SET 
								  	display_order = " . ($update->display_order - 1) . " 
								  WHERE id = " . $update->id);
		}
	}

    /**
     * Get previous position
     *
     * @param $position - Initial position
     *
     * @return int
     */
	public static function getPrevious($position)
	{

		$db = Database::get();

		$previous = $db->query("SELECT menu_id FROM gui_menu WHERE display_order = " . ($position - 1))->fetchObject()->menu_id;

		return $previous;
	}


    /**
     * Get next position
     *
     * @param $position - Initial position
     *
     * @return int
     */
	public static function getNext($position)
	{

		$db = Database::get();

		$next = $db->query("SELECT menu_id FROM gui_menu WHERE display_order = " . ($position + 1))->fetchObject()->menu_id;

		return $next;
	}


    /**
     * Move menu display position Up
     *
     * @param $id               - MenuModel id
     * @param $previous_id      - MenuModel previous id
     * @param $current_position - Current display position
     *
     * @throws Exception
     */
	public static function goUp($id, $previous_id, $current_position)
	{

		$db = Database::get();

		$statements = [
			"UPDATE gui_menu SET display_order = " . ($current_position - 1) . " WHERE menu_id = " . $id,
			"UPDATE gui_menu SET display_order = $current_position WHERE menu_id = " . $previous_id,
		];

		Database::transaction($db, $statements);
	}

    /**
     * Move menu display position Down
     *
     * @param $id               - MenuModel id
     * @param $next_id          - MenuModel next id
     * @param $current_position - Current display position
     *
     * @throws Exception
     */
	public static function goDown($id, $next_id, $current_position)
	{

		$db = Database::get();

		$statements = [
			"UPDATE gui_menu SET display_order = " . ($current_position + 1) . " WHERE menu_id = " . $id,
			"UPDATE gui_menu SET display_order = $current_position WHERE menu_id = " . $next_id,
		];

		Database::transaction($db, $statements);
	}
}