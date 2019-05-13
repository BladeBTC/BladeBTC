<?php

namespace App\Models;

use App\Helpers\Database;

/**
 * Class RbacModel
 *
 * @package App\Models\
 */
class RbacModel
{

	/**
	 * Get rbac items description.
	 *
	 * @return \PDOStatement
	 */
	public static function getDescriptionById($rbac_id)
	{
		$db = Database::get();

		$description = $db->query("SELECT description FROM ae_rbac_items WHERE id = $rbac_id")->fetchObject()->description;

		return $description;
	}

	/**
	 * Validate if group have item assigned
	 *
	 * @param $rbac_item_id - RbacModel item ID
	 * @param $group_id     - GroupModel ID
	 *
	 * @return bool
	 */
	public static function can($rbac_item_id, $group_id)
	{
		$db = Database::get();

		$count = $db->query("	SELECT
										  COUNT(*) AS C
										FROM
										  ae_rbac_assignment
										WHERE
										  rbac_items_id = $rbac_item_id AND 
										  group_id = $group_id")->fetchObject()->C;

		return $count > 0 ? true : false;

	}

	/**
	 * Get all rbac items.
	 *
	 * @return \PDOStatement
	 */
	public static function getItems()
	{
		$db = Database::get();

		$items = $db->query("SELECT * FROM ae_rbac_items");

		return $items;
	}

	/**
	 * Get all rbac assignments.
	 *
	 * @return \PDOStatement
	 */
	public static function getAssignments($order_by = "group_id")
	{
		$db = Database::get();

		$assignment = $db->query("SELECT * FROM ae_rbac_assignment ORDER BY $order_by");

		return $assignment;
	}


	/**
	 * Add rbac item
	 *
	 * @param $description - Item description
	 *
	 * @return string - Item Id
	 */
	public static function addItem($description)
	{
		$db = Database::get();

		$query = "	INSERT INTO 
					  ae_rbac_items (
						description
					  )
					VALUES (
						:description 
					)";

		$sth = $db->prepare($query);

		$sth->execute([
			"description" => $description,
		]);

		return $db->lastInsertId();
	}

	/**
	 * Add rbac assigment
	 *
	 * @param $group_id     - GroupModel ID
	 * @param $rbac_item_id - Item ID
	 */
	public static function addAssignment($group_id, $rbac_item_id)
	{
		$db = Database::get();

		$query = "	INSERT INTO 
					  ae_rbac_assignment (
						group_id, 
						rbac_items_id
					  )
					VALUES (
						:group_id,
						:rbac_item_id 
					)";

		$sth = $db->prepare($query);

		$sth->execute([
			"group_id"     => $group_id,
			"rbac_item_id" => $rbac_item_id,
		]);
	}

	/**
	 * Remove rbac assignment
	 *
	 * @param $group_id     - GroupModel ID
	 * @param $rbac_item_id - Item ID
	 */
	public static function removeAssignment($group_id, $rbac_item_id)
	{
		$db = Database::get();

		Database::transaction($db, [
			"DELETE FROM ae_rbac_assignment WHERE group_id = $group_id AND rbac_items_id = $rbac_item_id",
		]);
	}
}