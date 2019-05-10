<?php

namespace App\Models;

use App\Helpers\Database;
use Exception;
use PDO;

/**
 * Class ModuleModel
 *
 * @package App\Models
 */
class ModuleModel
{
	/**
	 * Get all modules
	 *
	 * @return \PDOStatement
	 */
	public static function getAll()
	{
		$db = Database::get();

		$module = $db->query("SELECT * FROM ae_module");

		return $module;
	}

	/**
	 * Get module
	 *
	 * @param $module_id - ModuleModel id
	 *
	 * @return mixed
	 */
	public static function getById($module_id, $fetch_assoc = false)
	{
		$db = Database::get();

		if ($fetch_assoc) {
			$module = $db->query("SELECT * FROM ae_module WHERE id = $module_id")->fetch(PDO::FETCH_ASSOC);
		} else {
			$module = $db->query("SELECT * FROM ae_module WHERE id = $module_id")->fetchObject();
		}

		return $module;
	}

	/**
	 * Get all group authorised on this module.
	 *
	 * @param $module_name - Module name
	 *
	 * @return array
	 */
	public static function getAccessGroupArray($module_name)
	{
		$db = Database::get();

		$data = $db->query("SELECT access_level FROM ae_module WHERE name = '$module_name'")->fetchObject()->access_level;

		return explode(";", $data);
	}

	/**
	 * Add visit to this page
	 *
	 * @param $module_name - Module name
	 */
	public static function addVisit($module_name)
	{
		$db = Database::get();

		$db->query("UPDATE ae_module SET visits = visits + 1, last_visit = NOW() WHERE name = '$module_name'");
	}

	/**
	 * Get module name
	 *
	 * @param $module_id - ModuleModel id
	 *
	 * @return mixed
	 */
	public static function getName($module_id)
	{
		$db = Database::get();
		$name = $db->query("SELECT name FROM ae_module WHERE id = $module_id")->fetchObject()->name;

		return $name;
	}

	/**
	 * Validate if module is dashboard for a group
	 *
	 * @param $module_id
	 *
	 * @return mixed
	 */
	public static function isDashboard($module_id)
	{
		$db = Database::get();
		$count = $db->query("SELECT COUNT(*) AS C FROM ae_group WHERE dashboard = '" . self::getName($module_id) . "'")->fetchObject()->C;

		if ($count > 0) {
			return true;
		}

		return false;
	}

	/**
	 * Create module
	 *
	 * @param $data - module Data
	 */
	public static function add($data)
	{
		$db = Database::get();

		$query = "		INSERT
						INTO
						  `ae_module`(
	`description`,
							`name`,
							`icon`,
							`access_level`,
							`parent`,
							`static`,
							`active`
						  )
						VALUES(
							:description,
							:name,
							:icon,
							:access_level,
							:parent,
							:static,
							:active
						)";

		$sth = $db->prepare($query);

		$sth->execute([
			"description"  => $data['description'],
			"name"         => $data['name'],
			"icon"         => $data['icon'],
			"parent"       => $data['parent'],
			"active"       => $data['active'],
			"static"       => $data['static'],
			"access_level" => $data['access_level'],
		]);
	}

	/**
	 * Update module
	 *
	 * @param $data - module Data
	 */
	public static function update($data)
	{
		$db = Database::get();

		$query = "	UPDATE
					  `ae_module`
					SET
					  `description` = :description,
					  `name` = :name,
					  `icon` = :icon,
					  `access_level` = :access_level,
					  `parent` = :parent,
					  `static` = :static,
					  `active` = :active
					WHERE
					  `id` = :id";

		$sth = $db->prepare($query);

		$sth->execute([
			"id"           => $data['id'],
			"description"  => $data['description'],
			"name"         => $data['name'],
			"icon"         => $data['icon'],
			"parent"       => $data['parent'],
			"active"       => $data['active'],
			"static"       => $data['static'],
			"access_level" => $data['access_level'],
		]);
	}

	/**
	 * Delete module
	 *
	 * @param $module_id - module ID
	 */
	public static function delete($module_id)
	{


		$db = Database::get();

		Database::transaction($db, [

			/**
			 * Delete module
			 */
			"DELETE FROM ae_module WHERE id = $module_id",

		]);
	}


	/**
	 * Register all new modules
	 *
	 * @param $name        - ModuleModel name
	 * @param $description - ModuleModel description
	 *
	 * @return bool|string
	 */
	public static function register($name, $description)
	{

		$db = Database::get();

		try {

			/**
			 * Check if module is already registered.
			 */
			$count = $db->query("SELECT COUNT(*) AS C FROM ae_module WHERE name = '$name'")->fetchObject()->C;


			if ($count == 0) {

				/**
				 * Register
				 */
				$query = "	INSERT
						INTO
						  `ae_module`(
							`description`,
							`name`,
							`icon`,
							`access_level`,
							`parent`
						  )
						VALUES(
						 	:description,
							:name,
							:icon,
							:access_level,
							:parent
						)";

				$sth = $db->prepare($query);

				$sth->execute([
					"description"  => "$description",
					"name"         => "$name",
					"icon"         => "fa-wrench",
					"access_level" => "-1",
					"parent"       => "-1",
				]);
			}


		} catch (Exception $e) {
			return $e->getMessage();
		}

		return true;
	}


}