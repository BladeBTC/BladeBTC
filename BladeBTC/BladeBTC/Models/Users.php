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
class Users
{

    private $_DB = null;
    private $_USER = null;

    /**
     * Users constructor.
     *
     * @param $telegramId
     */
    public function __construct($telegramId)
    {
        $this->_DB = Database::get();
        $data = $this->_DB->query("SELECT * FROM `users` WHERE `telegram_id` = " . $telegramId);
        if ($data->rowCount() > 0) {
            $this->_USER = $data->fetchObject();
        }
    }

    /**
     * Check if investment address exist
     *
     * @param $address
     *
     * @return bool
     */
    public static function checkExistByInvestmentAddress($address)
    {
        $db = Database::get();

        $data = $db->query("SELECT * FROM `users` WHERE `investment_address` = '".$address."'");
        if ($data->rowCount() > 0) {
            return true;
        }

        return false;
    }


    /**
     * Get telegram ID from address
     *
     * @param $address
     *
     * @return string
     */
    public static function getTelegramIDByInvestmentAddress($address)
    {
        $db = Database::get();

        $data = $db->query("SELECT `telegram_id` FROM `users` WHERE `investment_address` = '".$address."'")->fetchObject();

        return is_object($data) ? $data->telegram_id : null;
    }


    /**
     * Refresh user data
     */
    public function Refresh()
    {
        $data = $this->_DB->query("SELECT * FROM `users` WHERE `telegram_id` = " . $this->getTelegramId());
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
     *
     * @param $amount
     *
     * @throws Exception
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
        } catch (Exception $e) {
            $this->_DB->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Store invested
     *
     * @param $amount
     *
     * @throws Exception
     */
    public function setInvested($amount)
    {
        try {
            $this->_DB->beginTransaction();
            $this->_DB->query("   UPDATE
                                              `users`
                                            SET 
                                              `invested` = " . $this->_DB->quote($amount) . "
                                            WHERE
                                                `telegram_id` = " . $this->getTelegramId() . "
                                            ");
            $this->_DB->commit();
        } catch (Exception $e) {
            $this->_DB->rollBack();
            throw new Exception($e->getMessage());
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
     * @throws Exception
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
                                                `referral_link`
                                              )
                                            VALUES(
                                              " . $this->_DB->quote($data["username"]) . ",
                                              " . $this->_DB->quote($data["first_name"]) . ",
                                              " . $this->_DB->quote($data["last_name"]) . ",
                                              " . $this->_DB->quote($data["id"]) . ",
                                              " . $this->_DB->quote($referral_link) . "
                                            )");
            $this->_DB->commit();
        } catch (Exception $e) {
            $this->_DB->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Store investment address
     *
     * @param $investment_address
     *
     * @throws Exception
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
        } catch (Exception $e) {
            $this->_DB->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Store wallet address
     *
     * @param $wallet_address
     *
     * @throws Exception
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
        } catch (Exception $e) {
            $this->_DB->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Reinvest all account balance
     *
     * @throws Exception
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
            Investment::create($this->getTelegramId(), $balance);


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

            if (InvestmentPlan::getValueByName("interest_on_reinvest") == 1) {

                /**
                 * Get referent ID
                 */
                $referent_id = self::getReferentId();

                if (!is_null($referent_id)) {

                    /**
                     * Calculate commission
                     */
                    $rate = InvestmentPlan::getValueByName("commission_rate");
                    $commission = $balance * $rate / 100;
                    Users::giveCommission($referent_id, $commission);

                }
            }

            $this->_DB->query("   INSERT
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
									" . $this->_DB->quote($this->getTelegramId()) . ",
									" . $this->_DB->quote($balance) . ",
									" . $this->_DB->quote("") . ",
									" . $this->_DB->quote("") . ",
									" . $this->_DB->quote("") . ",
									" . $this->_DB->quote("") . ",
									" . $this->_DB->quote(1) . ",
									" . $this->_DB->quote("reinvest") . "
									)");


            $this->_DB->commit();
        } catch (Exception $e) {
            $this->_DB->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Update balance and payout
     *
     * @param $out_amount
     *
     * @param $transaction
     *
     * @throws Exception
     */
    public function updateBalance($out_amount, $transaction)
    {

        try {

            $this->_DB->beginTransaction();

            $this->_DB->query("   UPDATE
                                              `users`
                                            SET 
                                              `balance` = `balance` - " . $this->_DB->quote($out_amount) . ",
                                              `payout` = `payout` + " . $this->_DB->quote($out_amount) . "
                                            WHERE
                                                `telegram_id` = " . self::getTelegramId() . "
                                            ");

            $this->_DB->query("   INSERT
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
									" . $this->_DB->quote(self::getTelegramId()) . ",
									" . $this->_DB->quote($out_amount) . ",
									" . $this->_DB->quote(self::getWalletAddress()) . ",
									" . $this->_DB->quote($transaction->message) . ",
									" . $this->_DB->quote($transaction->tx_hash) . ",
									" . $this->_DB->quote($transaction->txid) . ",
									" . $this->_DB->quote(1) . ",
									" . $this->_DB->quote("withdraw") . "
									)");


            $this->_DB->commit();
        } catch (Exception $e) {

            $this->_DB->rollBack();
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Get total investment
     *
     * @return double
     */
    public function getNumberOfInvestment()
    {
        $db = Database::get();

        $count = $db->query("	SELECT COUNT(*) AS `C` FROM `investment` WHERE `telegram_id` = " . $this->getTelegramId())->fetchObject()->C;

        return $count;

    }


    /**
     * Get total investment
     *
     * @return double
     */
    public function getReferentId()
    {
        $db = Database::get();

        $telegram_id_referent = $db->query("SELECT `telegram_id_referent` FROM `referrals` WHERE `telegram_id_referred` = " . $this->getTelegramId())->fetchObject();

        return is_object($telegram_id_referent) ? $telegram_id_referent->telegram_id_referent : null;
    }

    /**
     * Give commission to referent
     *
     * @param $telegram_referent_id - Id of the referent
     *
     * @param $commission           - Commission to give
     *
     * @throws Exception
     */
    public static function giveCommission($telegram_referent_id, $commission)
    {
        $db = Database::get();

        try {
            $db->beginTransaction();

            $db->query("   UPDATE
                                        `users`
                                     SET 
                                        `commission` = `commission` + " . $db->quote($commission) . ",
                                        `balance` = `balance` + " . $db->quote($commission) . "
                                     WHERE
                                        `telegram_id` = " . $telegram_referent_id);


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
									" . $db->quote($telegram_referent_id) . ",
									" . $db->quote($commission) . ",
									" . $db->quote("") . ",
									" . $db->quote("") . ",
									" . $db->quote("") . ",
									" . $db->quote("") . ",
									" . $db->quote(1) . ",
									" . $db->quote("commission") . "
									)");


            $db->commit();
        } catch (Exception $e) {

            $db->rollBack();
            throw new Exception($e->getMessage());
        }
    }
}