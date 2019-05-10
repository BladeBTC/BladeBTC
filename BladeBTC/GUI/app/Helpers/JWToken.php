<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 2017-11-16
 * Time: 12:34
 */

namespace App\Helpers;

use Exception;
use Firebase\JWT\JWT;

class JWToken
{

	public static function encode($membre_id, $membre_name, $expire_time = 3600, $custom_data = [])
	{

		//Prepare data
		$issuer = getenv("ISSUER");
		$issuedAt = time();
		$expire = $issuedAt + $expire_time;
		$audience = getenv("AUDIENCE");
		$subject = $membre_id;
		$name = $membre_name;

		//Token as an array
		$data = [
			'iss'  => $issuer,             // Identifier (or, name) of the server or system issuing the token. Typically a DNS name, but doesn't have to be.
			'iat'  => $issuedAt,           // Date/time when the token was issued. (defaults to now)
			'exp'  => $expire,             // Date/time at which point the token is no longer valid. (defaults to one year from now)
			'aud'  => $audience,           // Intended recipient of this token; can be any string, as long as the other end uses the same string when validating the token. Typically a DNS name.
			'sub'  => $subject,            // Identifier (or, name) of the user this token represents. (user_id)
			'name' => $name                // Full Name
		];

		/**
		 * Add custom data to JWT
		 */
		$data = array_merge($data, $custom_data);

		//Get secret key
		$secretKey = base64_decode(getenv("JWT_KEY"));

		/*
		 * Encode the array to a JWT string.
		 */
		$jwt = JWT::encode(
			$data,      //Data to be encoded in the JWT
			$secretKey, // The signing key
			'HS512'     // Algorithm used to sign the token, see https://tools.ietf.org/html/draft-ietf-jose-json-web-algorithms-40#section-3
		);

		return $jwt;
	}


	/**
	 * Valid if token is valid and return data or error.
	 *
	 * @param      $jwt      - Json Web Token
	 * @param      $key      - Signing Key
	 * @param int  $leeway   - Leeway time in secondes
	 * @param bool $to_array - Return array instead of object
	 *
	 * @return array|object
	 */
	public static function decode($jwt, $leeway = 0, $to_array = false)
	{
		try {
			//Get secret key
			$secretKey = base64_decode(getenv("JWT_KEY"));

			JWT::$leeway = $leeway;
			$decoded = JWT::decode($jwt, $secretKey, ['HS512']);
			$decoded_array = (array)$decoded;
			if ($to_array) {
				return [
					"valid" => true,
					"data"  => $decoded_array,
				];
			}

			return [
				"valid" => true,
				"data"  => $decoded,
			];
		} catch (Exception $e) {

			return [
				"valid" => false,
				"data"  => $e->getMessage(),
			];
		}
	}
}