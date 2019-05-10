<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 2017-10-10
 * Time: 15:55
 */

namespace App\Helpers;


class Messages
{
	public static function success($msg)
	{
		echo '<div class="alert alert-success">';
		echo '<i class="fa fa-check-circle fa-fw"></i> ' . $msg;
		echo '</div>';
	}

	public static function info($msg)
	{
		echo '<div class="alert alert-info">';
		echo '<i class="fa fa-info-circle fa-fw"></i> ' . $msg;
		echo '</div>';
	}

	public static function warning($msg)
	{
		echo '<div class="alert alert-warning">';
		echo '<i class="fa fa-exclamation-triangle fa-fw"></i> ' . $msg;
		echo '</div>';
	}

	public static function error($msg)
	{
		echo '<div class="alert alert-danger">';
		echo '<i class="fa fa-exclamation-circle fa-fw"></i> ' . $msg;
		echo '</div>';
	}
}