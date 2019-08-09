<?php

namespace BladeBTC\GUI\Controllers;

use BladeBTC\GUI\Helpers\Form;
use BladeBTC\GUI\Helpers\Request;
use BladeBTC\GUI\Models\InvestmentPlansModel;
use Exception;

class ManageInvestmentPlan
{

    /**
     * Handle multiples actions in Investment Plan management.
     *
     * @return null|string
     * @throws Exception
     */
    public static function action()
    {

        $action = Request::get('action');

        $msg = null;
        switch ($action) {

            case "edit_plan" :

                $plan_data = InvestmentPlansModel::getById(Request::get('id'), true);
                Form::save($plan_data, true);
                $msg = "The Investment Plan has been loaded.";

                break;

            case "delete_plan":

                InvestmentPlansModel::delete(Request::get('id'));

                $msg = "The Investment Plan has been deleted.";

                break;


            case "activate_plan":

                InvestmentPlansModel::activatePlan(Request::get('id'));

                $msg = "The Investment Plan has been activated.";

                break;

        }

        return $msg;
    }


    /**
     * Add new account
     *
     * @return bool
     * @throws Exception
     */
    public static function addInvestmentPlan()
    {

        /**
         * Save form data
         */
        Form::save(Request::post());

        /**
         * Form value
         */
        $minimum_invest = Request::post('minimum_invest');
        $minimum_reinvest = Request::post('minimum_reinvest');
        $minimum_payout = Request::post('minimum_payout');
        $base_rate = Request::post('base_rate');
        $contract_day = Request::post('contract_day');
        $commission_rate = Request::post('commission_rate');
        $timer_time_hour = Request::post('timer_time_hour');
        $required_confirmations = Request::post('required_confirmations');
        $interest_on_reinvest = Request::post('interest_on_reinvest');
        $withdraw_fee = Request::post('withdraw_fee');

        /**
         * Validate minimum invest
         */
        if (empty($minimum_invest)) {
            Form::remove('minimum_invest');
            throw new Exception("You must enter a minimum invest amount.");
        }

        if ($minimum_invest <= 0) {
            Form::remove('minimum_invest');
            throw new Exception("You must enter a minimum invest greater than 0.");
        }

        /**
         * Validate minimum reinvest
         */
        if (empty($minimum_reinvest)) {
            Form::remove('minimum_reinvest');
            throw new Exception("You must enter a minimum reinvest amount.");
        }

        if ($minimum_reinvest <= 0) {
            Form::remove('minimum_invest');
            throw new Exception("You must enter a minimum reinvest greater than 0.");
        }

        /**
         * Validate minimum payout
         */
        if (empty($minimum_payout)) {
            Form::remove('minimum_payout');
            throw new Exception("You must enter a minimum payout amount.");
        }

        if ($minimum_payout <= 0) {
            Form::remove('minimum_payout');
            throw new Exception("You must enter a minimum payout greater than 0.");
        }

        /**
         * Validate base rate
         */
        if (empty($base_rate)) {
            Form::remove('base_rate');
            throw new Exception("You must enter a base rate percentage.");
        }

        if ($base_rate < 0 || $base_rate > 100) {
            Form::remove('base_rate');
            throw new Exception("The base rate percentage should be between 0 and 100");
        }

        /**
         * Validate contract day
         */
        if (empty($contract_day)) {
            Form::remove('contract_day');
            throw new Exception("You must enter a contract time in days.");
        }

        /**
         * Validate commission rate
         */
        if (empty($commission_rate)) {
            Form::remove('commission_rate');
            throw new Exception("You must enter a commission rate percentage.");
        }

        if ($base_rate < 0 || $commission_rate > 100) {
            Form::remove('commission_rate');
            throw new Exception("The commission rate percentage should be between 0 and 100");
        }

        /**
         * Validate Time Time Hours
         */
        if ($timer_time_hour == -1) {
            Form::remove('timer_time_hour');
            throw new Exception("You must select an interest interval.");
        }

        /**
         * Validate Confirmation Required
         */
        if (empty($required_confirmations)) {
            Form::remove('required_confirmations');
            throw new Exception("You must enter a number of confirmation required.");
        }

        if ($base_rate < 1 || $required_confirmations > 10) {
            Form::remove('required_confirmations');
            throw new Exception("The confirmation required should be between 1 and 10");
        }

        /**
         * Validate Interest on reinvest
         */
        if ($interest_on_reinvest == -1) {
            Form::remove('interest_on_reinvest');
            throw new Exception("You must select if we need to give interest on reinvest.");
        }

        /**
         * Validate Withdraw fee
         */
        if ($withdraw_fee == -1) {
            Form::remove('withdraw_fee');
            throw new Exception("You must select the withdraw fee.");
        }

        /**
         * Prepare data
         */
        $plan = [
            "minimum_invest" => $minimum_invest,
            "minimum_reinvest" => $minimum_reinvest,
            "minimum_payout" => $minimum_payout,
            "base_rate" => $base_rate,
            "contract_day" => $contract_day,
            "commission_rate" => $commission_rate,
            "timer_time_hour" => $timer_time_hour,
            "required_confirmations" => $required_confirmations,
            "interest_on_reinvest" => $interest_on_reinvest,
            "withdraw_fee" => $withdraw_fee,
        ];

        try {

            InvestmentPlansModel::create($plan);

            Form::destroy();

            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Edit account
     *
     * @return bool
     * @throws Exception
     */
    public static function editInvestmentPlan()
    {

        /**
         * Save form data
         */
        Form::update(Request::post());

        /**
         * Form value
         */
        $minimum_invest = Request::post('minimum_invest');
        $minimum_reinvest = Request::post('minimum_reinvest');
        $minimum_payout = Request::post('minimum_payout');
        $base_rate = Request::post('base_rate');
        $contract_day = Request::post('contract_day');
        $commission_rate = Request::post('commission_rate');
        $timer_time_hour = Request::post('timer_time_hour');
        $required_confirmations = Request::post('required_confirmations');
        $interest_on_reinvest = Request::post('interest_on_reinvest');
        $withdraw_fee = Request::post('withdraw_fee');
        $plan_id = Form::getReturn('id');

        /**
         * Validate minimum invest
         */
        if (empty($minimum_invest)) {
            Form::remove('minimum_invest');
            throw new Exception("You must enter a minimum invest amount.");
        }

        if ($minimum_invest <= 0) {
            Form::remove('minimum_invest');
            throw new Exception("You must enter a minimum invest greater than 0.");
        }

        /**
         * Validate minimum reinvest
         */
        if (empty($minimum_reinvest)) {
            Form::remove('minimum_reinvest');
            throw new Exception("You must enter a minimum reinvest amount.");
        }

        if ($minimum_reinvest <= 0) {
            Form::remove('minimum_invest');
            throw new Exception("You must enter a minimum reinvest greater than 0.");
        }

        /**
         * Validate minimum payout
         */
        if (empty($minimum_payout)) {
            Form::remove('minimum_payout');
            throw new Exception("You must enter a minimum payout amount.");
        }

        if ($minimum_payout <= 0) {
            Form::remove('minimum_payout');
            throw new Exception("You must enter a minimum payout greater than 0.");
        }

        /**
         * Validate base rate
         */
        if (empty($base_rate)) {
            Form::remove('base_rate');
            throw new Exception("You must enter a base rate percentage.");
        }

        if ($base_rate < 0 || $base_rate > 100) {
            Form::remove('base_rate');
            throw new Exception("The base rate percentage should be between 0 and 100");
        }

        /**
         * Validate contract day
         */
        if (empty($contract_day)) {
            Form::remove('contract_day');
            throw new Exception("You must enter a contract time in days.");
        }

        /**
         * Validate commission rate
         */
        if (empty($commission_rate)) {
            Form::remove('commission_rate');
            throw new Exception("You must enter a commission rate percentage.");
        }

        if ($base_rate < 0 || $commission_rate > 100) {
            Form::remove('commission_rate');
            throw new Exception("The commission rate percentage should be between 0 and 100");
        }

        /**
         * Validate Time Time Hours
         */
        if ($timer_time_hour == "null") {
            Form::remove('timer_time_hour');
            throw new Exception("You must select an interest interval.");
        }

        /**
         * Validate Confirmation Required
         */
        if (empty($required_confirmations)) {
            Form::remove('required_confirmations');
            throw new Exception("You must enter a number of confirmation required.");
        }

        if ($base_rate < 1 || $required_confirmations > 10) {
            Form::remove('required_confirmations');
            throw new Exception("The confirmation required should be between 1 and 10");
        }

        /**
         * Validate Interest on reinvest
         */
        if ($interest_on_reinvest == "null") {
            Form::remove('interest_on_reinvest');
            throw new Exception("You must select if we need to give interest on reinvest.");
        }

        /**
         * Validate Withdraw fee
         */
        if ($withdraw_fee == "null") {
            Form::remove('withdraw_fee');
            throw new Exception("You must select the withdraw fee.");
        }


        /**
         * Prepare data
         */
        $plan = [
            "id" => $plan_id,
            "minimum_invest" => $minimum_invest,
            "minimum_reinvest" => $minimum_reinvest,
            "minimum_payout" => $minimum_payout,
            "base_rate" => $base_rate,
            "contract_day" => $contract_day,
            "commission_rate" => $commission_rate,
            "timer_time_hour" => $timer_time_hour,
            "required_confirmations" => $required_confirmations,
            "interest_on_reinvest" => $interest_on_reinvest,
            "withdraw_fee" => $withdraw_fee,
        ];

        try {

            InvestmentPlansModel::update($plan);

            Form::destroy();

            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}

