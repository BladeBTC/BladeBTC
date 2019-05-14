<?php

namespace BladeBTC\GUI\Models;

use BladeBTC\GUI\Helpers\Database;
use Exception;
use PDO;

/**
 * Class MailGroupModel
 *
 * @package App\Models\
 */
class MailGroupModel
{

	/**
	 * Get all mail group
	 *
	 * @return array|null
	 */
	public static function getAll()
	{
		$db = Database::get();

		$query = "SELECT * FROM gui_mail_group ORDER BY id ASC";

		$groups = $db->query($query)->fetchAll(PDO::FETCH_OBJ);

		return count($groups) > 0 ? $groups : null;
	}


	/**
	 * Get group name.
	 *
	 * @param $group_id - Group id
	 *
	 * @return null
	 */
	public static function getGroupName($group_id)
	{
		$db = Database::get();

		$query = "SELECT `group_name` FROM gui_mail_group WHERE `id` = $group_id";

		$group_name = $db->query($query)->fetchObject();

		return is_object($group_name) ? $group_name->group_name : null;
	}


	/**
	 * Add mail group
	 *
	 * @param $group_name - Group Name
	 *
	 * @throws Exception
	 */
	public static function addGroup($group_name)
	{
		$db = Database::get();

		$query = "INSERT INTO 
					  gui_mail_group (
						group_name
					  )
					VALUES (
						:group_name 
					)";

		$sth = $db->prepare($query);

		$sth->execute([
			"group_name" => $group_name,
		]);

		return $db->lastInsertId();
	}
}
