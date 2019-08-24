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

    /**
     * Format number as USD
     *
     * @param $amount - amount
     *
     * @return string
     */
    public static function FormatUSD($amount)
    {
        $url = "https://blockchain.info/stats?format=json";
        $stats = json_decode(file_get_contents($url), true);
        $btcValue = $stats['market_price_usd'];

        $convertedCost = $btcValue * $amount;

        return number_format($convertedCost, 2, ".", " ");
    }
}