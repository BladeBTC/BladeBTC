<?php

namespace BladeBTC\Models;

use BladeBTC\Helpers\Database;
use PDO;

/**
 * Class InvestmentPlan
 *
 * @package BladeBTC\Models
 */
class InvestmentPlan
{
    /**
     * Get currently active plan settings
     *
     * @param      $name
     *
     * @return mixed
     */
    public static function getValueByName($name)
    {
        $db = Database::get();
        $plan = $db->query("SELECT * FROM investment_plans WHERE active=1")->fetch(PDO::FETCH_ASSOC);

        return $plan[$name];
    }
}
