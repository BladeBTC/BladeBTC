<?php

namespace BladeBTC\Models;


use BladeBTC\Helpers\Database;

/**
 * Class Investment
 *
 * @package BladeBTC\Models
 */
class Investment
{

	/**
	 * Create investment in database
	 *
	 * @param $telegram_id - User telegram ID
	 * @param $amount      - Amount
	 * @param $rate        - Rate
	 */
	public static function create($telegram_id, $amount, $rate)
	{

		$db = Database::get();

		try {

			$db->beginTransaction();
			$db->query("	INSERT
									INTO
									  `investment`(
										`telegram_id`,
										`amount`,
										`rate`,
										`contract_end_date`
									  )
									VALUES(
									 " . $db->quote($telegram_id) . ",
									 " . $db->quote($amount) . ",
									 " . $db->quote($rate) . ",
									 NOW() + INTERVAL " . (getenv("CONTRACT_DAY")) . " DAY
									)");
			$db->commit();
		} catch (\Exception $e) {
			$db->rollBack();
			throw new \Exception($e->getMessage());
		}
	}


	/**
	 * Get active investment total
	 *
	 * @param $telegram_id - Telegram ID
	 */
	public static function getActiveInvestmentTotal($telegram_id)
	{
		$db = Database::get();

		$total = 0;
		$investment = $db->query("	SELECT `amount` FROM `investment` WHERE contract_end_date > NOW() AND `telegram_id` = " . $telegram_id);
		while ($row = $investment->fetchObject()) {
			$total += $row->amount;
		}

		return $total;

	}


	/**
	 * Get active investment list
	 *
	 * @param $telegram_id - Telegram ID
	 */
	public static function getActiveInvestment($telegram_id)
	{
		$db = Database::get();

		$investment = [];
		$investment_qry = $db->query("	SELECT * FROM `investment` WHERE contract_end_date > NOW() AND `telegram_id` = " . $telegram_id);
		while ($row = $investment_qry->fetchObject()) {
			$investment[] = $row;
		}

		return $investment;

	}
}