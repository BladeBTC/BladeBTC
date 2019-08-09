<?php

namespace BladeBTC\Models;


use BladeBTC\Helpers\Database;
use Exception;

/**
 * Users model
 * Class Users
 *
 * @package BladeBTC\Models
 */
class Transactions
{

	/**
	 * Log transaction detail.
	 *
	 * @param $data - Transaction data
	 *
	 * @throws Exception
	 */
	public static function log($data)
	{

		$db = Database::get();

		try {

			$db->beginTransaction();
			$db->query("   INSERT
									INTO
									  `transactions`(
										`telegram_id`,
										`amount`,
										`withdraw_address`,
										`message`,
										`tx_hash`,
										`tx_id`,
										`status`,
										`type`
									  )
									VALUES(
									" . $db->quote($data["telegram_id"]) . ",
									" . $db->quote($data["amount"]) . ",
									" . $db->quote($data["withdraw_address"]) . ",
									" . $db->quote($data["message"]) . ",
									" . $db->quote($data["tx_hash"]) . ",
									" . $db->quote($data["tx_id"]) . ",
									" . $db->quote($data["status"]) . ",
									" . $db->quote($data["type"]) . "
									)");
			$db->commit();
		} catch (Exception $e) {
			$db->rollBack();
			throw new Exception($e->getMessage());
		}
	}
}