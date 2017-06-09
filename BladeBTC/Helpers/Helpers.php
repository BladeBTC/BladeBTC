<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 09/06/2017
 * Time: 13:18
 */

namespace BladeBTC\Helpers;


class Helpers
{
    public static function btc($amount)
    {
        return number_format($amount, 6, ".", " ");
    }
}