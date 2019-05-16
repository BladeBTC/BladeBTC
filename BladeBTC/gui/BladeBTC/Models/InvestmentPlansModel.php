<?php

namespace BladeBTC\GUI\Models;

use BladeBTC\GUI\Helpers\Database;
use BladeBTC\GUI\Helpers\Session;
use DebugBar\DataCollector\PDO\TraceablePDOStatement;
use Exception;
use PDO;

/**
 * Class InvestmentPlansModel
 *
 * @package BladeBTC\GUI\Models\
 */
class InvestmentPlansModel
{
    /**
     * @param bool $include_deleted
     *
     * @return bool|TraceablePDOStatement
     */
    public static function getAll($include_deleted = false)
    {
        $db = Database::get();

        if ($include_deleted) {
            $plans = $db->query("SELECT * FROM investment_plans");
        }
        else {
            $plans = $db->query("SELECT * FROM investment_plans WHERE deleted = 0");
        }

        return $plans;
    }

    /**
     * Get all data from account
     *
     * @param      $investment_plan
     * @param bool $fetch_assoc - Fetch mode
     *
     * @return mixed
     */
    public static function getById($investment_plan, $fetch_assoc = false)
    {
        $db = Database::get();

        if ($fetch_assoc) {
            $plan = $db->query("SELECT * FROM investment_plans WHERE id=$investment_plan")->fetch(PDO::FETCH_ASSOC);
        }
        else {
            $plan = $db->query("SELECT * FROM investment_plans WHERE id=$investment_plan")->fetchObject();
        }

        return $plan;
    }

    /**
     * Get currently active plan
     *
     * @param bool $fetch_assoc
     *
     * @return mixed
     */
    public static function getActivePlan($fetch_assoc = false)
    {
        $db = Database::get();

        if ($fetch_assoc) {
            $plan = $db->query("SELECT id FROM investment_plans WHERE active=1")->fetch(PDO::FETCH_ASSOC);
        }
        else {
            $plan = $db->query("SELECT id FROM investment_plans WHERE active=1")->fetchObject();
        }

        return $plan;
    }

    /**
     * Create an investment plan
     *
     * @param $data
     *
     * @throws Exception
     */
    public static function create($data)
    {
        $db = Database::get();

        $query = "	INSERT
                    INTO
                        `investment_plans`(
                            `minimum_invest`,
                            `minimum_reinvest`,
                            `minimum_payout`,
                            `base_rate`,
                            `contract_day`,
                            `commission_rate`,
                            `timer_time_hour`,
                            `required_confirmations`,
                            `interest_on_reinvest`,
                            `withdraw_fee`,
                            `created_account_id`
                        )
                    VALUES(
                            :minimum_invest,
                            :minimum_reinvest,
                            :minimum_payout,
                            :base_rate,
                            :contract_day,
                            :commission_rate,
                            :timer_time_hour,
                            :required_confirmations,
                            :interest_on_reinvest,
                            :withdraw_fee,
                            :created_account_id
                    )";

        $sth = $db->prepare($query);

        $sth->execute([
            "minimum_invest" => $data['minimum_invest'],
            "minimum_reinvest" => $data['minimum_reinvest'],
            "minimum_payout" => $data['minimum_payout'],
            "base_rate" => $data['base_rate'],
            "contract_day" => $data['contract_day'],
            "commission_rate" => $data['commission_rate'],
            "timer_time_hour" => $data['timer_time_hour'],
            "required_confirmations" => $data['required_confirmations'],
            "interest_on_reinvest" => $data['interest_on_reinvest'],
            "withdraw_fee" => $data['withdraw_fee'],
            "created_account_id" => Session::get('account_id'),
        ]);
    }

    /**
     * Update an investment plan
     *
     * @param $data
     *
     * @throws Exception
     */
    public static function update($data)
    {
        $db = Database::get();

        $query = "    UPDATE
                        `investment_plans`
                        SET
                            `minimum_invest` = :minimum_invest,
                            `minimum_reinvest` = :minimum_reinvest,
                            `minimum_payout` = :minimum_payout,
                            `base_rate` = :base_rate,
                            `contract_day` = :contract_day,
                            `commission_rate` = :commission_rate,
                            `timer_time_hour` = :timer_time_hour,
                            `required_confirmations` = :required_confirmations,
                            `interest_on_reinvest` = :interest_on_reinvest,
                            `withdraw_fee` = :withdraw_fee
                        WHERE
                            id = :id";

        $sth = $db->prepare($query);

        $sth->execute([
            "id" => $data['id'],
            "minimum_invest" => $data['minimum_invest'],
            "minimum_reinvest" => $data['minimum_reinvest'],
            "minimum_payout" => $data['minimum_payout'],
            "base_rate" => $data['base_rate'],
            "contract_day" => $data['contract_day'],
            "commission_rate" => $data['commission_rate'],
            "timer_time_hour" => $data['timer_time_hour'],
            "required_confirmations" => $data['required_confirmations'],
            "interest_on_reinvest" => $data['interest_on_reinvest'],
            "withdraw_fee" => $data['withdraw_fee'],
        ]);
    }

    /**
     * Delete an Investment Plan
     *
     * @param $investment_plan
     *
     * @throws Exception
     */
    public static function delete($investment_plan)
    {

        $current_active = self::getActivePlan();
        if ($current_active->id == $investment_plan) {
            throw new Exception("This investment plan is currently active. Activate another investment plan before deleting this one.");
        }

        $db = Database::get();

        $query = "	UPDATE 
						investment_plans 
					SET 
						deleted = 1,
					    deleted_account_id = :deleted_account_id,
					    deleted_date = NOW()
					WHERE 
						id = :id";

        $sth = $db->prepare($query);

        $sth->execute([
            "id" => $investment_plan,
            "deleted_account_id" => Session::get("account_id"),
        ]);
    }

    /**
     * Activate an Investment Plan
     *
     * @param $investment_plan
     *
     * @throws Exception
     */
    public static function activatePlan($investment_plan)
    {
        $db = Database::get();

        $statements = [
            "UPDATE investment_plans SET active = 0 WHERE active = 1",
            "UPDATE investment_plans SET active = 1 WHERE id = " . $investment_plan,
        ];

        Database::transaction($db, $statements);
    }
}
