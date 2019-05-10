<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 2017-11-22
 * Time: 16:50
 */

namespace App\Helpers;

use Milon\Barcode\DNS1D;

class Barcode
{

	/**
	 * Get barcode HTML
	 *
	 * @param $barcode - Numeric barcode
	 */
	public static function getHTML($barcode, $weight = 2, $height = 30, $type = "UPCA")
	{
		$d = new DNS1D();
		$d->setStorPath(__DIR__ . "/cache/");
		if (!empty($barcode)) {
			echo $d->getBarcodeHTML($barcode, $type, $weight, $height);
		}
	}


	/**
	 * Get base 64 barcode PNG
	 *
	 * @param $barcode - Numeric barcode
	 */
	public static function getBase64PNG($barcode, $weight = 2, $height = 30, $type = "UPCA")
	{

		try {
			$d = new DNS1D();
			$d->setStorPath(__DIR__ . "/cache/");

			if (!empty($barcode)) {
				echo "data:image/png;base64," . $d->getBarcodePNG($barcode, $type, $weight, $height);
			}
		}
		catch (\Exception $e){

			//Exception with barcode return nothing
			echo "";
		}
	}
}