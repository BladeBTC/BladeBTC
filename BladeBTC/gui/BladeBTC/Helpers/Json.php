<?php

namespace BladeBTC\GUI\Helpers;


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