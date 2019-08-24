<?php

namespace BladeBTC\Models;


use BladeBTC\Helpers\Btc;
use BladeBTC\Helpers\Database;
use Exception;

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
     * @param $amount - Amount
     *
     * @throws Exception
     */
    public static function create($telegram_id, $amount)
    {

        $db = Database::get();

        try {

            $db->beginTransaction();
            $db->query("	INSERT
									INTO
									  `investment`(
										`telegram_id`,
										`amount`,
										`contract_end_date`
									  )
									VALUES(
									 " . $db->quote($telegram_id) . ",
									 " . $db->quote($amount) . ",
									 NOW() + INTERVAL " . (InvestmentPlan::getValueByName("contract_day")) . " DAY
									)");

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
									" . $db->quote($telegram_id) . ",
									" . $db->quote($amount) . ",
									" . $db->quote("") . ",
									" . $db->quote("") . ",
									" . $db->quote("") . ",
									" . $db->quote("") . ",
									" . $db->quote(1) . ",
									" . $db->quote("investment") . "
									)");

            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw new Exception($e->getMessage());
        }
    }


    /**
     * Get active investment total
     *
     * @param $telegram_id - Telegram ID
     *
     * @return int
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
     *
     * @return array
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

    /**
     * Remove contract end put investment in balance
     *
     * @throws Exception
     */
    public static function endContract()
    {

        $db = Database::get();

        try {
            $db->beginTransaction();
            $contracts = $db->query("SELECT * FROM `investment` WHERE contract_end_date <= NOW()");
            while ($contract = $contracts->fetchObject()) {

                /**
                 * Refund investment into balance
                 */
                $db->query("   UPDATE
                                              `users`
                                            SET 
                                              `balance` = `balance` + " . $contract->amount . "
                                            WHERE
                                                `telegram_id` = " . $contract->telegram_id . "
                                            ");

                /**
                 * Delete contract
                 */
                $db->query("DELETE FROM `investment` WHERE `id` = " . $contract->id);

            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw new Exception($e->getMessage());
        }
    }


    /**
     * Give interest from contract
     *
     * @throws Exception
     */
    public static function giveInterest()
    {

        $db = Database::get();

        try {
            $db->beginTransaction();
            $contracts = $db->query("SELECT * FROM `investment` WHERE contract_end_date > NOW()");
            while ($contract = $contracts->fetchObject()) {

                $interest = (InvestmentPlan::getValueByName("base_rate") / (24 / InvestmentPlan::getValueByName("timer_time_hour"))) * $contract->amount / 100;
                $db->query("   UPDATE
                                              `users`
                                            SET 
                                              `profit` = `profit` + " . $db->quote($interest) . ",
                                              `balance` = `balance` + " . $db->quote($interest) . "
                                            WHERE
                                                `telegram_id` = " . $contract->telegram_id . "
                                            ");

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
									" . $db->quote($contract->telegram_id) . ",
									" . $db->quote($interest) . ",
									" . $db->quote("") . ",
									" . $db->quote("") . ",
									" . $db->quote("") . ",
									" . $db->quote("") . ",
									" . $db->quote(1) . ",
									" . $db->quote("interest") . "
									)");


                /**
                 * Send user message - Notification of interest
                 */
                $apiToken = BotSetting::getValueByName('app_id');
                $data = [
                    'parse_mode' => 'HTML',
                    'chat_id' => $contract->telegram_id,
                    'text' => "\xF0\x9F\x98\x81 Congratulation! An amount of <b>" . Btc::Format($interest) . "</b> BTC ( $" . Btc::FormatUSD($interest) . " USD ) in interest was added to your balance. \xF0\x9F\x98\x81"
                ];
                file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?" . http_build_query($data));

            }
            $db->commit();
        } catch (Exception $e) {
            $db->rollBack();
            throw new Exception($e->getMessage());
        }
    }
}