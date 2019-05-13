<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 2017-10-13
 * Time: 22:06
 */

namespace App\Models;

use App\Helpers\Database;
use Exception;
use PDO;

class GroupModel
{
	/**
	 * Create group
	 *
	 * @param $data - GroupModel Data
	 */
	public static function add($data)
	{
		$db = Database::get();

		$query = "	INSERT INTO 
					  ae_group (
					  	group_id,
						group_name, 
						dashboard
					  )
					VALUES (
						:group_id,
						:group_name, 
						:dashboard 
					)";

		$sth = $db->prepare($query);

		$sth->execute([
			"group_id"   => self::getNextGroupId(),
			"group_name" => $data["group_name"],
			"dashboard"  => $data["dashboard"],
		]);
	}

	/**
	 * Update group
	 *
	 * @param $data - GroupModel Data
	 */
	public static function update($data)
	{
		$db = Database::get();

		$query = "	UPDATE
					  ae_group
					SET
					  group_name = :group_name,
					  dashboard = :dashboard
					WHERE
					  group_id = :group_id";

		$sth = $db->prepare($query);

		$sth->execute([
			"group_id"   => $data["group_id"],
			"group_name" => $data["group_name"],
			"dashboard"  => $data["dashboard"],
		]);
	}

	/**
	 * Delete group
	 *
	 * @param $group_id - MenuModel ID
	 */
	public static function delete($group_id)
	{

		if ($group_id == -1) {
			throw new Exception("La suppression de ce groupe n'est pas permise.");
		}

		$db = Database::get();

		try {

			$db->beginTransaction();

			$statements = [

				/**
				 * Delete group
				 */
				"DELETE FROM ae_group WHERE group_id = $group_id",


				/**
				 * Remove group from account
				 */
				"UPDATE ae_account SET account_group = 0 WHERE account_group = $group_id",
			];

			foreach ($statements as $statement) {
				$db->exec($statement);
			}

			/**
			 * Remove group from each modules
			 */
			$modules = $db->query("SELECT id, access_level FROM ae_module");
			while ($module = $modules->fetchObject()) {
				$current_access_level = explode(";", $module->access_level);
				$new_access_level = null;
				foreach ($current_access_level as $lvl) {
					if ($lvl != $group_id) {
						$new_access_level .= $lvl . ";";
					}
				}

				$new_access_level = substr($new_access_level, 0, -1);

				$db->exec("UPDATE ae_module SET access_level = '$new_access_level' WHERE id = " . $module->id);

			}

			$db->commit();

		} catch (Exception $e) {

			$db->rollBack();
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Get all group
	 *
	 * @return \PDOStatement
	 */
	public static function getAll()
	{
		$db = Database::get();

		$groups = $db->query("SELECT * FROM ae_group");

		return $groups;
	}

	/**
	 * Get group
	 *
	 * @param $group_id - group id
	 *
	 * @return mixed
	 */
	public static function getByGroupId($group_id, $fetch_assoc = false)
	{
		$db = Database::get();

		if ($fetch_assoc) {
			$group = $db->query("SELECT * FROM ae_group WHERE group_id = $group_id")->fetch(PDO::FETCH_ASSOC);
		} else {
			$group = $db->query("SELECT * FROM ae_group WHERE group_id = $group_id")->fetchObject();
		}

		return $group;
	}

	/**
	 * Get group name
	 *
	 * @param $group_id - group id
	 *
	 * @return mixed
	 */
	public static function getNameById($group_id)
	{
		$db = Database::get();

		if ($group_id == 0) {
			return "<span class='label label-danger'>Compte Orphelin</span>";
		}

		$name = $db->query("SELECT group_name FROM ae_group WHERE group_id = $group_id")->fetchObject()->group_name;

		return $name;
	}

	/**
	 * Get dashboard of the group
	 *
	 * @param $group_id - group id
	 *
	 * @return mixed
	 */
	public static function getDashboardById($group_id)
	{
		$db = Database::get();

		$dashboard = $db->query("SELECT dashboard FROM ae_group WHERE group_id = $group_id")->fetchObject()->dashboard;

		return $dashboard;
	}

	/**
	 * Get next group Id
	 *
	 * @return mixed
	 */
	public static function getNextGroupId()
	{
		return Database::getNextAutoIncrementId("ae_group");
	}
}


