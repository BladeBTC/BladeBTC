<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 2018-03-20
 * Time: 15:59
 */

namespace App\Helpers;


class Json
{
	/*
	 * Return Json
	 */
	public static function toJson($data)
	{
		header('Content-type: application/json');
		echo json_encode($data);
	}
}