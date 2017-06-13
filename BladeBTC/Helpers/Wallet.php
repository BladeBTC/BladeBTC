<?php

namespace BladeBTC\Helpers;

/**
 * Class Wallet
 *
 * @package BladeBTC\Helpers
 * @see     https://bittrex.com/Home/Api
 *            https://github.com/Cannacoin-Project/Bittrex-API-Client/blob/master/client.php
 */
class Wallet
{

	public static function getBalance()
	{

		$path = 'account/getbalance';
		$params = [
			"currency" => "BTC",
		];

		$response = self::apiQuery($path, $params);
		echo json_encode($response);
	}

	private static function apiQuery($path, array $req = [])
	{
		$req['apikey'] = getenv("API_KEY");
		$req['nonce'] = time();

		$queryString = http_build_query($req, '', '&');
		$requestUrl = getenv("API_URL") . $path . '?' . $queryString;
		$sign = hash_hmac('sha512', $requestUrl, getenv("PRIVATE_KEY"));

		static $curlHandler = null;

		if (is_null($curlHandler)) {
			$curlHandler = curl_init();
			curl_setopt($curlHandler, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curlHandler, CURLOPT_HTTPHEADER, ['apisign:' . $sign]);
			curl_setopt($curlHandler, CURLOPT_HTTPGET, true);
			curl_setopt($curlHandler, CURLOPT_URL, $requestUrl);
			curl_setopt($curlHandler, CURLOPT_SSL_VERIFYPEER, true);
		}

		// run the query
		$response = curl_exec($curlHandler);

		if ($response === false) {
			throw new \Exception('Could not get reply: ' . curl_error($curlHandler));
		}

		$json = json_decode($response, true);
		if (!$json) {
			throw new \Exception('Invalid data received, please make sure connection is working and requested API exists');
		}

		return $json;
	}

}