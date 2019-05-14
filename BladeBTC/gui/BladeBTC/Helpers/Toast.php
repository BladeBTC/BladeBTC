<?php

namespace BladeBTC\GUI\Helpers;

class Toast
{

	/**
	 * Add toast message of type error to list
	 *
	 * @param $message - Message
	 */
	public static function error($message)
	{
		if (isset($_SESSION['toast'])) {
			array_push($_SESSION['toast'], "<script type='text/javascript'>$.toast({
															heading: 'Erreur',
															text: \"$message\",
															showHideTransition: 'plain',
															icon: 'error',
															hideAfter: 7000,
															position: 'bottom-right'
														})</script>");
		} else {
			$_SESSION['toast'][] = "<script type='text/javascript'>$.toast({
															heading: 'Erreur',
															text: \"$message\",
															showHideTransition: 'plain',
															icon: 'error',
															hideAfter: 7000,
															position: 'bottom-right'
														})</script>";
		}
	}

	/**
	 * Add toast message of type warning to list
	 *
	 * @param $message - Message
	 */
	public static function warning($message)
	{
		if (isset($_SESSION['toast'])) {
			array_push($_SESSION['toast'], "<script type='text/javascript'>$.toast({
															heading: 'Attention',
															text: \"$message\",
															showHideTransition: 'plain',
															icon: 'warning',
															hideAfter: 7000,
															position: 'bottom-right'
														})</script>");
		} else {
			$_SESSION['toast'][] = "<script type='text/javascript'>$.toast({
															heading: 'Attention',
															text: \"$message\",
															showHideTransition: 'plain',
															icon: 'warning',
															hideAfter: 7000,
															position: 'bottom-right'
														})</script>";
		}
	}

	/**
	 * Add toast message of type success to list
	 *
	 * @param $message - Message
	 */
	public static function success($message)
	{
		if (isset($_SESSION['toast'])) {
			array_push($_SESSION['toast'], "<script type='text/javascript'>$.toast({
															heading: 'Succès',
															text: \"$message\",
															showHideTransition: 'plain',
															icon: 'success',
															hideAfter: 7000,
															position: 'bottom-right'
														})</script>");
		} else {
			$_SESSION['toast'][] = "<script type='text/javascript'>$.toast({
															heading: 'Succès',
															text: \"$message\",
															showHideTransition: 'plain',
															icon: 'success',
															hideAfter: 7000,
															position: 'bottom-right'
														})</script>";
		}
	}

	/**
	 * Display toast message
	 */
	public static function display()
	{
		if (isset($_SESSION['toast'])) {
			foreach ($_SESSION['toast'] as $toast) {
				echo $toast;
			}

			unset($_SESSION['toast']);
		}
	}
}