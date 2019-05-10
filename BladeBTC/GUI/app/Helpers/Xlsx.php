<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 2018-01-17
 * Time: 10:58
 */

namespace App\Helpers;

use XLSXWriter;

class Xlsx
{

	/**
	 * Exemple :
	 *
	 * https://github.com/mk-j/PHP_XLSXWriter/tree/master/examples
	 */

	//style 		allowed values
	//font 			Arial, Times New Roman, Courier New, Comic Sans MS
	//font-size 	8,9,10,11,12 ...
	//font-style 	bold, italic, underline, strikethrough or multiple ie: 'bold,italic'
	//border 		left, right, top, bottom, or multiple ie: 'top,left'
	//border-style 	thin, medium, thick, dashDot, dashDotDot, dashed, dotted, double, hair, mediumDashDot, mediumDashDotDot, mediumDashed, slantDashDot
	//border-color 	#RRGGBB, ie: #ff99cc or #f9c
	//color 		#RRGGBB, ie: #ff99cc or #f9c
	//fill 			#RRGGBB, ie: #eeffee or #efe
	//halign 		general, left, right, justify, center
	//valign 		bottom, center, distributed

	//simple formats 	format code
	//string 			@
	//integer 			0
	//date 				YYYY-MM-DD
	//datetime 			YYYY-MM-DD HH:MM:SS
	//price 			#,##0.00
	//dollar 			[$$-1009]#,##0.00;[RED]-[$$-1009]#,##0.00
	//euro 				#,##0.00 [$€-407];[RED]-#,##0.00 [$€-407]


	/**
	 * Write xlsx file
	 */
	public static function write($file, $data, $header = null, $report_style = null)
	{
		$writer = new XLSXWriter();

		if (!is_null($header))
			$writer->writeSheetHeader('Sheet1', $header, ['font' => 'Arial', 'font-size' => 12, 'font-style' => 'bold', 'fill' => '#000', 'halign' => 'center', 'color' => '#fff']);

		$i = 0;
		foreach ($data as $row) {
			if (!is_null($report_style)) {
				$writer->writeSheetRow('Sheet1', $row, $report_style[$i]);
			} else {
				$writer->writeSheetRow('Sheet1', $row);
			}
			$i++;
		}

		$writer->writeToFile($file);
	}
}