<?php

namespace BladeBTC\Models;


use BladeBTC\Helpers\Database;

/**
 * Class Referrals
 *
 * @package BladeBTC\Models
 */
class Referrals
{

	/**
	 * Create investment in database
	 *
	 * @param $telegram_id - User telegram ID
	 * @param $amount      - Amount
	 * @param $rate        - Rate
	 */
	public static function BindAccount($referral_link, $telegram_id_reffered)
	{

		$db = Database::get();

		try {

			$db->beginTransaction();


			$referent = $db->query("SELECT `telegram_id` FROM `users` WHERE `referral_link` = '" . $referral_link . "'")->fetchObject();
			if (is_object($referent) && !empty($referent->telegram_id)) {

				$db->query("	INSERT
									INTO
									  `referrals`(
										`telegram_id_referent`,
										`telegram_id_referred`
									  )
									VALUES(
									 " . $db->quote($referent->telegram_id) . ",
									 " . $db->quote($telegram_id_reffered) . "
									)");

			}


			$db->commit();
		} catch (\Exception $e) {
			$db->rollBack();
			throw new \Exception($e->getMessage());
		}
	}


}