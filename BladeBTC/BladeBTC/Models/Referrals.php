<?php

namespace BladeBTC\Models;

use BladeBTC\Helpers\Database;
use Exception;

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
     * @param $referral_link
     * @param $telegram_id_reffered
     * @throws Exception
     */
	public static function BindAccount($referral_link, $telegram_id_reffered)
	{

		$db = Database::get();

		try {

			$db->beginTransaction();

			/**
			 * Validate referral link and get referent telegram ID
			 */
			$referent = $db->query("SELECT `telegram_id` FROM `users` WHERE `referral_link` = '" . $referral_link . "'")->fetchObject();
			if (is_object($referent) && !empty($referent->telegram_id)) {

				/**
				 * Check if referent id and referred id is the same
				 */
				if ($referent->telegram_id != $telegram_id_reffered) {


					/**
					 * Check if user is already refered by another account.
					 */
					$count = $db->query("SELECT COUNT(*) AS `C` FROM `referrals` WHERE `telegram_id_referred` = '" . $telegram_id_reffered . "'")->fetchObject()->C;

					if ($count <= 0) {

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


				}
			}

			$db->commit();
		} catch (Exception $e) {
			$db->rollBack();
			throw new Exception($e->getMessage());
		}
	}

	/**
	 * Get total of referrals
	 *
	 * @param $telegram_referent_id
	 *
	 * @return mixed
	 */
	public static function getTotalReferrals($telegram_referent_id)
	{
		$db = Database::get();
		$referrals = $db
			->query("SELECT COUNT(*) AS `C` FROM `referrals` WHERE `telegram_id_referent` = '" . $telegram_referent_id . "'")
			->fetchObject()
			->C;

		return $referrals;
	}

	/**
	 * Get active referrals
	 *
	 * @param $telegram_referent_id
	 *
	 * @return mixed
	 */
	public static function getActiveReferrals($telegram_referent_id)
	{
		$db = Database::get();
		$actives = 0;
		$referrals = $db->query("SELECT `telegram_id_referred` FROM `referrals` WHERE `telegram_id_referent` = '" . $telegram_referent_id . "'");
		while ($referral = $referrals->fetchObject()) {
			$count = $db->query("SELECT 
												COUNT(*) AS `C` 
											  FROM 
											  	`investment`
											  WHERE 
											  	`telegram_id` ='" . $referral->telegram_id_referred . "' AND
											  	`contract_end_date` > NOW()")->fetchObject()->C;
			if ($count > 0) {
				$actives++;
			}
		}

		return $actives;
	}

	/**
	 * Get referrals invest
	 *
	 * @param $telegram_referent_id
	 *
	 * @return mixed
	 */
	public static function getReferralsInvest($telegram_referent_id)
	{
		$db = Database::get();
		$total = 0;
		$referrals = $db->query("SELECT `telegram_id_referred` FROM `referrals` WHERE `telegram_id_referent` = '" . $telegram_referent_id . "'");
		while ($referral = $referrals->fetchObject()) {
			$amounts = $db->query("SELECT 
												`amount`
											  FROM 
											  	`investment`
											  WHERE 
											  	`telegram_id` ='" . $referral->telegram_id_referred . "' AND
											  	`contract_end_date` > NOW()");
			while ($amount = $amounts->fetchObject()) {
				$total += $amount->amount;
			}
		}

		return $total;
	}
}