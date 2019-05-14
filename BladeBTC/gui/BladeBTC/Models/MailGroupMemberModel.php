<?php

namespace BladeBTC\GUI\Models;

use BladeBTC\GUI\Helpers\Database;
use Exception;
use PDO;

/**
 * Class MailGroupMemberModel
 *
 * @package App\Models\
 */
class MailGroupMemberModel
{

	/**
	 * Get all members from a specific mail group
	 *
	 * @param $group_id - Group ID
	 */
	public static function getAllMembersFromGroupID($group_id, $fetch_mode = PDO::FETCH_OBJ)
	{
		$db = Database::get();

		$members = $db->query("SELECT * FROM gui_mail_group_member WHERE mail_group_id = $group_id")->fetchAll($fetch_mode);

		return $members;
	}

	/**
	 * Add an email to a specific group.
	 *
	 * @param $mail_group_id - Mail group ID
	 * @param $email         - Email
	 * @param $alias         - Alias
	 *
	 * @throws Exception
	 */
	public static function add($mail_group_id, $email, $alias)
	{
		$db = Database::get();

		$query = "INSERT INTO 
					  gui_mail_group_member (
						mail_group_id,
						email,
						alias
					  )
					VALUES (
						:mail_group_id,
						:email,
						:alias
					)";

		$sth = $db->prepare($query);

		$sth->execute([
			"mail_group_id" => $mail_group_id,
			"email"         => $email,
			"alias"         => $alias,
		]);


		return $db->lastInsertId();
	}


	/**
	 * Delete a member from a group.
	 *
	 * @param $id - Group Member ID
	 *
	 * @throws Exception
	 */
	public static function delete($id)
	{
		$db = Database::get();

		Database::transaction($db, [
			"DELETE FROM gui_mail_group_member WHERE id = $id",
		]);
	}
}
