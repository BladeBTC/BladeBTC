<?php

namespace BladeBTC\GUI\Helpers;

use DebugBar\DataCollector\PDO\PDOCollector;
use DebugBar\StandardDebugBar;

class Debugbar
{


	/**
	 * @var Debugbar
	 * Instance of this class.
	 */
	private static $debugbar;


	/**
	 * Initialize this class and
	 * force Database class to register
	 * all available connection to the
	 * PDO collector.
	 */
	public static function init()
	{
		self::$debugbar = new StandardDebugBar();
		Database::init();
	}


	/**
	 * Display HTML that we need to put in <head> tag.
	 */
	public static function getHeaderHTML()
	{
		$debugbarRenderer = self::$debugbar->getJavascriptRenderer();
		echo $debugbarRenderer->renderHead();
	}


	/**
	 * Display HTML that we need to put in <body> tag.
	 */
	public static function getBodyHTML()
	{
		$debugbarRenderer = self::$debugbar->getJavascriptRenderer();
		echo $debugbarRenderer->render();
	}


	/**
	 * Register PDO Collector
	 *
	 * @param PDOCollector $collector
	 */
	public static function registerPDOCollector(PDOCollector $collector)
	{
		self::$debugbar->addCollector($collector);
	}
}