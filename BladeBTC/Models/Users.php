<?php

namespace BladeBTC\Models;


use BladeBTC\Helpers\Database;

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

    public function exist()
    {
        if (is_null($this->_USER)) {
            return false;
        }
        return true;
    }

    public function getId()
    {
        return $this->_USER->id;
    }

    public function getTelegramUsername()
    {
        return $this->_USER->telegram_username;
    }

    public function getTelegramFirstName()
    {
        return $this->_USER->telegram_first;
    }

    public function getTelegramLastName()
    {
        return $this->_USER->telegram_last;
    }

    public function getTelegramId()
    {
        return $this->_USER->telegram_id;
    }

    public function getBalance()
    {
        return $this->_USER->balance;
    }

    public function getInvested()
    {
        return $this->_USER->invested;
    }

    public function getActiveInvestment()
    {
        return $this->_USER->active_investment;
    }

    public function getProfit()
    {
        return $this->_USER->profit;
    }

    public function getCommission()
    {
        return $this->_USER->commission;
    }

    public function getPayout()
    {
        return $this->_USER->payout;
    }

    public function getInvestmentAddress()
    {
        return $this->_USER->investment_address;
    }

    public function getWalletAddress()
    {
        return $this->_USER->wallet_address;
    }

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
                                              '" . $this->_DB->quote($data["username"]) . "',
                                              '" . $this->_DB->quote($data["first_name"]) . "',
                                              '" . $this->_DB->quote($data["last_name"]) . "',
                                              '" . $this->_DB->quote($data["id"]) . "'
                                            )");
            $this->_DB->commit();
            return true;
        } catch (\Exception $e) {
            $this->_DB->rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}