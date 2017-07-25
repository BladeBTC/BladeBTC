<?php

namespace BladeBTC\Models;


use BladeBTC\Helpers\Database;

/**
 * Users model
 * Class Users
 *
 * @package BladeBTC\Models
 */
class Users
{

	private $_DB = null;
	private $_USER = null;

	/**
	 * Users constructor.
	 */
	public function __construct($telegramId)
	{
		$this->_DB = Database::get();
		$data = $this->_DB->query("SELECT * FROM users WHERE telegram_id = " . $telegramId);
		if ($data->rowCount() > 0) {
			$this->_USER = $data->fetchObject();
		}
	}

	/**
	 * Get all chat id
	 */
	public static function getAllChatId()
	{
		$db = Database::get();
		$data = $db->query("SELECT telegram_id FROM users");

		$id = null;
		while ($row = $data->fetchObject()) {
			$id[] = $row->telegram_id;
		}

		return $id;
	}


	/**
	 * Refresh user data
	 */
	public function Refresh()
	{
		$data = $this->_DB->query("SELECT * FROM users WHERE telegram_id = " . $this->getTelegramId());
		if ($data->rowCount() > 0) {
			$this->_USER = $data->fetchObject();
		}
	}

	/**
	 * Get Telegram ID
	 *
	 * @return mixed
	 */
	public function getTelegramId()
	{
		return $this->_USER->telegram_id;
	}

	/**
	 * Check if user exist
	 */
	public function exist()
	{
		if (is_null($this->_USER)) {
			return false;
		}

		return true;
	}

	/**
	 * Get database ID
	 *
	 * @return mixed
	 */
	public function getId()
	{
		return $this->_USER->id;
	}

	/**
	 * Get Telegram username
	 *
	 * @return mixed
	 */
	public function getTelegramUsername()
	{
		return $this->_USER->telegram_username;
	}

	/**
	 * Get Telegram first name
	 *
	 * @return mixed
	 */
	public function getTelegramFirstName()
	{
		return $this->_USER->telegram_first;
	}

	/**
	 * Get Telegram last name
	 *
	 * @return mixed
	 */
	public function getTelegramLastName()
	{
		return $this->_USER->telegram_last;
	}

	/**
	 * Get account balance
	 *
	 * @return mixed
	 */
	public function getBalance()
	{
		return $this->_USER->balance;
	}

	/**
	 * Get account invested
	 *
	 * @return mixed
	 */
	public function getInvested()
	{
		return $this->_USER->invested;
	}

	/**
	 * Get profit
	 *
	 * @return mixed
	 */
	public function getProfit()
	{
		return $this->_USER->profit;
	}

	/**
	 * Get commission
	 *
	 * @return mixed
	 */
	public function getCommission()
	{
		return $this->_USER->commission;
	}

	/**
	 * Get payout
	 *
	 * @return mixed
	 */
	public function getPayout()
	{
		return $this->_USER->payout;
	}

	/**
	 * Get Investment Address
	 *
	 * @return mixed
	 */
	public function getInvestmentAddress()
	{
		return $this->_USER->investment_address;
	}

	/**
	 * Get last confirmed
	 *
	 * @return mixed
	 */
	public function getLastConfirmed()
	{
		return $this->_USER->last_confirmed;
	}

	/**
	 * Store last confirmed
	 */
	public function setLastConfirmed($amount)
	{
		try {
			$this->_DB->beginTransaction();
			$this->_DB->query("   UPDATE
                                              `users`
                                            SET 
                                              `last_confirmed` = " . $this->_DB->quote($amount) . "
                                            WHERE
                                                `telegram_id` = " . $this->getTelegramId() . "
                                            ");
			$this->_DB->commit();
		} catch (\Exception $e) {
			$this->_DB->rollBack();
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * Get wallet address
	 *
	 * @return mixed
	 */
	public function getWalletAddress()
	{
		return $this->_USER->wallet_address;
	}

	/**
	 * Get referral link
	 *
	 * @return mixed
	 */
	public function getReferralLink()
	{
		return $this->_USER->referral_link;
	}

	/**
	 * Create user
	 *
	 * @param $data - Data user
	 *
	 * @throws \Exception
	 */
	public function create($data)
	{
		try {
			$this->_DB->beginTransaction();

			/**
			 * Generate referral link
			 */
			$referral_link = uniqid();

			$this->_DB->query("   INSERT
                                            INTO
                                              `users`(
                                                `telegram_username`,
                                                `telegram_first`,
                                                `telegram_last`,
                                                `telegram_id`,
                                                `rate`,
                                                `referral_link`
                                              )
                                            VALUES(
                                              " . $this->_DB->quote($data["username"]) . ",
                                              " . $this->_DB->quote($data["first_name"]) . ",
                                              " . $this->_DB->quote($data["last_name"]) . ",
                                              " . $this->_DB->quote($data["id"]) . ",
                                              " . $this->_DB->quote(getenv("BASE_RATE")) . ",
                                              " . $this->_DB->quote($referral_link) . "
                                            )");
			$this->_DB->commit();
		} catch (\Exception $e) {
			$this->_DB->rollBack();
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * Store investment address
	 */
	public function setInvestmentAddress($investment_address)
	{
		try {
			$this->_DB->beginTransaction();
			$this->_DB->query("   UPDATE
                                              `users`
                                            SET 
                                              `investment_address` = " . $this->_DB->quote($investment_address) . "
                                            WHERE
                                                `telegram_id` = " . $this->getTelegramId() . "
                                            ");
			$this->_DB->commit();
		} catch (\Exception $e) {
			$this->_DB->rollBack();
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * Store wallet address
	 */
	public function setWalletAddress($wallet_address)
	{
		try {
			$this->_DB->beginTransaction();
			$this->_DB->query("   UPDATE
                                              `users`
                                            SET 
                                              `wallet_address` = " . $this->_DB->quote($wallet_address) . "
                                            WHERE
                                                `telegram_id` = " . $this->getTelegramId() . "
                                            ");
			$this->_DB->commit();
		} catch (\Exception $e) {
			$this->_DB->rollBack();
			throw new \Exception($e->getMessage());
		}
	}

	/**
	 * Reinvest all account balance
	 *
	 * @throws \Exception
	 */
	public function Reinvest()
	{
		try {
			$this->_DB->beginTransaction();


			/**
			 * Recover balance
			 */
			$balance = $this->_DB->query("    SELECT 
															`balance`
														FROM
															`users`
														WHERE
															`telegram_id` = " . $this->getTelegramId() . "
														")->fetchObject()->balance;

			/**
			 * Create investment
			 */
			Investment::create($this->getTelegramId(), $balance, $this->getUserRate());


			/**
			 * Put balance to 0
			 */
			$this->_DB->query("   UPDATE
                                              `users`
                                            SET 
                                              `balance` = " . $this->_DB->quote(0) . "
                                            WHERE
                                                `telegram_id` = " . $this->getTelegramId() . "
                                            ");


			/**
			 * Update invested
			 */
			$this->_DB->query("   UPDATE
                                              `users`
                                            SET 
                                              `invested` = `invested` + " . $this->_DB->quote($balance) . "
                                            WHERE
                                                `telegram_id` = " . $this->getTelegramId() . "
                                            ");

			/**
			 * Give bonus to referent
			 */

			if (getenv("INTEREST_ON_REINVEST") == 1) {

				$referent_id = $this->_DB->query("   SELECT
                                              `telegram_id_referent`
                                            FROM 
                                              `referrals`
                                            WHERE
                                                `telegram_id_referred` = " . $this->getTelegramId() . "
                                            ")->fetchObject();

				if (is_object($referent_id) && !empty($referent_id->telegram_id_referent)) {

					/**
					 * Calculate commision
					 */
					$rate = getenv("COMMISSION_RATE");
					$commission = $balance * $rate / 100;

					$this->_DB->query("   UPDATE
                                              `users`
                                            SET 
                                              `commission` = `commission` + " . $this->_DB->quote($commission) . ",
                                              `balance` = `balance` + " . $this->_DB->quote($commission) . "
                                            WHERE
                                                `telegram_id` = " . $referent_id->telegram_id_referent . "
                                            ");

				}
			}


			/**
			 * Log transaction
			 */
			Transactions::log([
				"telegram_id"      => $this->getTelegramId(),
				"amount"           => $balance,
				"withdraw_address" => "",
				"message"          => "",
				"tx_hash"          => "",
				"notice"           => "",
				"status"           => 1,
				"type"             => "reinvest",
			]);

			$this->_DB->commit();
		} catch (\Exception $e) {
			$this->_DB->rollBack();
			throw new \Exception($e->getMessage());
		}
	}

	public function getUserRate()
	{
		return $this->_USER->rate;
	}
}