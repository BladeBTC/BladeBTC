<?php

namespace BladeBTC\Models;


use BladeBTC\Helpers\Database;

/**
 * Users model
 * Class Users
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
     * @return mixed
     */
    public function getId()
    {
        return $this->_USER->id;
    }

    /**
     * Get Telegram username
     * @return mixed
     */
    public function getTelegramUsername()
    {
        return $this->_USER->telegram_username;
    }

    /**
     * Get Telegram first name
     * @return mixed
     */
    public function getTelegramFirstName()
    {
        return $this->_USER->telegram_first;
    }

    /**
     * Get Telegram last name
     * @return mixed
     */
    public function getTelegramLastName()
    {
        return $this->_USER->telegram_last;
    }

    /**
     * Get account balance
     * @return mixed
     */
    public function getBalance()
    {
        return $this->_USER->balance;
    }

    /**
     * Get account invested
     * @return mixed
     */
    public function getInvested()
    {
        return $this->_USER->invested;
    }

    /**
     * Get active investment
     * @return mixed
     */
    public function getActiveInvestment()
    {
        return $this->_USER->active_investment;
    }

    /**
     * Get profit
     * @return mixed
     */
    public function getProfit()
    {
        return $this->_USER->profit;
    }

    /**
     * Get commission
     * @return mixed
     */
    public function getCommission()
    {
        return $this->_USER->commission;
    }

    /**
     * Get payout
     * @return mixed
     */
    public function getPayout()
    {
        return $this->_USER->payout;
    }

    /**
     * Get Investment Address
     * @return mixed
     */
    public function getInvestmentAddress()
    {
        return $this->_USER->investment_address;
    }

    /**
     * Get wallet address
     * @return mixed
     */
    public function getWalletAddress()
    {
        return $this->_USER->wallet_address;
    }

    /**
     * Create user
     * @param $data - Data user
     * @throws \Exception
     */
    public function create($data)
    {
        try {
            $this->_DB->beginTransaction();
            $this->_DB->query("   INSERT
                                            INTO
                                              `users`(
                                                `telegram_username`,
                                                `telegram_first`,
                                                `telegram_last`,
                                                `telegram_id`
                                              )
                                            VALUES(
                                              " . $this->_DB->quote($data["username"]) . ",
                                              " . $this->_DB->quote($data["first_name"]) . ",
                                              " . $this->_DB->quote($data["last_name"]) . ",
                                              " . $this->_DB->quote($data["id"]) . "
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
     * Get Telegram ID
     * @return mixed
     */
    public function getTelegramId()
    {
        return $this->_USER->telegram_id;
    }
}