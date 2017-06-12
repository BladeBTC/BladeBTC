<?php

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

	/**
	 * Format number as BTC
	 *
	 * @param $amount - amount
	 *
	 * @return string
	 */
	public static function Format($amount)
	{
		return number_format($amount, 8, ".", " ");
	}
}