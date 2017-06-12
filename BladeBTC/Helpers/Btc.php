<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 12/06/2017
 * Time: 11:07
 */

namespace BladeBTC\Helpers;


class Btc
{
	/**
	 * Bitcoin to Satoshi
	 *
	 * @param $amount - Bitcoin amount
	 *
	 * @return mixed
	 */
	public static function BtcToSatoshi($amount)
	{
		return $amount * 100000000;
	}


	/**
	 * Satoshi to Bitcoin
	 *
	 * @param $amount - Satoshi amount
	 *
	 * @return float|int
	 */
	public static function SatoshiToBitcoin($amount)
	{
		return $amount / 100000000;
	}
}