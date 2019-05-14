<?php

namespace BladeBTC\GUI\Helpers;

use EmailValidator\Validator as EmailValidator;

class Utils
{

	/**
	 * This function act as print_r but with nice display.
	 *
	 * @param $object - $data object
	 */
	public static function print_r2($object)
	{
		echo "<pre>";
		print_r($object);
		echo "</pre>";
	}

	/**
	 * Format phone number 450-456-4565
	 *
	 * @param $number - Input number
	 *
	 * @return mixed - Output number
	 */
	public static function pnf($number)
	{
		return preg_replace('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', '($1) $2-$3', $number);
	}

	/**
	 * Format number
	 *
	 * @param     $number  - Input number
	 * @param int $decimal - Output number
	 */
	public static function nf($float, $precision = 2, $no_space = false)
	{
		if ($no_space) {
			return number_format(round($float, $precision, PHP_ROUND_HALF_EVEN), $precision, '.', '');
		}

		return number_format(round($float, $precision, PHP_ROUND_HALF_EVEN), $precision, '.', ' ');
	}

	/**
	 * Format Date
	 *
	 * @param $format
	 * @param $timestamp
	 *
	 * @return false|string
	 */
	public static function dateFromTimeStamp($format, $timestamp)
	{
		if (!empty($timestamp)) {
			return date($format, strtotime($timestamp));
		}

		return '';
	}

	/**
	 * Get user IP address
	 *
	 * @return mixed - IP
	 */
	public static function getIP()
	{
		$ip = $_SERVER['REMOTE_ADDR'];

		return $ip;
	}

	/**
	 * Push to associative array
	 *
	 * @param $array
	 * @param $key
	 * @param $value
	 *
	 * @return mixed
	 */
	public static function array_push_assoc($array, $key, $value)
	{
		$array[$key] = $value;

		return $array;
	}

	/**
	 * put link active
	 *
	 * @param $page - Page name
	 *
	 * @return bool
	 */
	public static function active($page)
	{
		$current_page = basename($_SERVER['PHP_SELF']);
		if ($current_page == $page) {
			echo 'class="active"';

			return true;
		}

		return false;
	}

	/**
	 * Partially validate email
	 * (Only validate email format)
	 *
	 * @param string $email - Email
	 */
	public static function isEmail($email)
	{

		$validator = new EmailValidator();

		return $validator->isEmail($email);
	}

	/**
	 * Remove http:// or https://
	 *
	 * @param $str
	 *
	 * @return mixed
	 */
	public static function strip_http($str)
	{
		$str = preg_replace('#^https?://#', '', $str);

		return $str;
	}


	/**
	 * Get product image
	 *
	 * @param     $product
	 * @param int $img
	 *
	 * @return null|string
	 */
	public static function getImage($product, $img = -1, $size = 50)
	{
		/**
		 * Images
		 */
		$images = null;

		/**
		 * Explode SKU to get image from master for satellite product
		 */
		$explode_img = explode("-", $product)[0];

		/**
		 * Website ROOT
		 */
		$root = $_SERVER['DOCUMENT_ROOT'];

		/**
		 * Lightbox HTML
		 */
		$lightbox_html = null;

		/**
		 * Return Image
		 */
		switch ($img) {

			case -1 :


				//img maitre 1
				if (file_exists($root . '/dist/img/products/' . strtoupper($explode_img) . '.jpg')) {
					$path = Path::productImg() . '/' . strtoupper($explode_img) . '.jpg';
					$lightbox_html .= "<a href=\"$path\" data-lightbox=\"$explode_img\"><img src=\"$path\" height=\"$size\"></a>";

				} //No image
				else {
					$path = Path::img() . '/no_image.jpg';
					$lightbox_html .= "<a href=\"$path\" data-lightbox=\"$explode_img\"><img src=\"$path\" height=\"$size\"></a>";
				}

				//img maitre 2
				if (file_exists($root . '/dist/img/products/' . strtoupper($explode_img) . '_2.jpg')) {
					$path = Path::productImg() . '/' . strtoupper($explode_img) . '_2.jpg';
					$lightbox_html .= "<a href=\"$path\" data-lightbox=\"$explode_img\"><img src=\"$path\" height=\"$size\"></a>";

				} //No image
				else {
					$path = Path::img() . '/no_image.jpg';
					$lightbox_html .= "<a href=\"$path\" data-lightbox=\"$explode_img\"><img src=\"$path\" height=\"$size\"></a>";
				}

				//img maitre 3
				if (file_exists($root . '/dist/img/products/' . strtoupper($explode_img) . '_3.jpg')) {
					$path = Path::productImg() . '/' . strtoupper($explode_img) . '_3.jpg';
					$lightbox_html .= "<a href=\"$path\" data-lightbox=\"$explode_img\"><img src=\"$path\" height=\"$size\"></a>";

				} //No image
				else {
					$path = Path::img() . '/no_image.jpg';
					$lightbox_html .= "<a href=\"$path\" data-lightbox=\"$explode_img\"><img src=\"$path\" height=\"$size\"></a>";
				}

				//img maitre 4
				if (file_exists($root . '/dist/img/products/' . strtoupper($explode_img) . '_4.jpg')) {
					$path = Path::productImg() . '/' . strtoupper($explode_img) . '_4.jpg';
					$lightbox_html .= "<a href=\"$path\" data-lightbox=\"$explode_img\"><img src=\"$path\" height=\"$size\"></a>";

				} //No image
				else {
					$path = Path::img() . '/no_image.jpg';
					$lightbox_html .= "<a href=\"$path\" data-lightbox=\"$explode_img\"><img src=\"$path\" height=\"$size\"></a>";
				}

				break;

			case 1 :

				//img maitre 1
				if (file_exists($root . '/dist/img/products/' . strtoupper($explode_img) . '.jpg')) {
					$path = Path::productImg() . '/' . strtoupper($explode_img) . '.jpg';
					$lightbox_html .= "<a href=\"$path\" data-lightbox=\"$explode_img\"><img src=\"$path\" height=\"$size\"></a>";
				} //No image
				else {
					$path = Path::img() . '/no_image.jpg';
					$lightbox_html .= "<a href=\"$path\" data-lightbox=\"$explode_img\"><img src=\"$path\" height=\"$size\"></a>";
				}

				break;

			case 2 :

				//img maitre 2
				if (file_exists($root . '/dist/img/products/' . strtoupper($explode_img) . '_2.jpg')) {
					$path = Path::productImg() . '/' . strtoupper($explode_img) . '_2.jpg';
					$lightbox_html .= "<a href=\"$path\" data-lightbox=\"$explode_img\"><img src=\"$path\" height=\"$size\"></a>";
				} //No image
				else {
					$path = Path::img() . '/no_image.jpg';
					$lightbox_html .= "<a href=\"$path\" data-lightbox=\"$explode_img\"><img src=\"$path\" height=\"$size\"></a>";
				}

				break;

			case 3 :

				//img maitre 3
				if (file_exists($root . '/dist/img/products/' . strtoupper($explode_img) . '_3.jpg')) {
					$path = Path::productImg() . '/' . strtoupper($explode_img) . '_3.jpg';
					$lightbox_html .= "<a href=\"$path\" data-lightbox=\"$explode_img\"><img src=\"$path\" height=\"$size\"></a>";
				} //No image
				else {
					$path = Path::img() . '/no_image.jpg';
					$lightbox_html .= "<a href=\"$path\" data-lightbox=\"$explode_img\"><img src=\"$path\" height=\"$size\"></a>";
				}

				break;

			case 4 :

				//img maitre 4
				if (file_exists($root . '/dist/img/products/' . strtoupper($explode_img) . '_4.jpg')) {
					$path = Path::productImg() . '/' . strtoupper($explode_img) . '_4.jpg';
					$lightbox_html .= "<a href=\"$path\" data-lightbox=\"$explode_img\"><img src=\"$path\" height=\"$size\"></a>";
				} //No image
				else {
					$path = Path::img() . '/no_image.jpg';
					$lightbox_html .= "<a href=\"$path\" data-lightbox=\"$explode_img\"><img src=\"$path\" height=\"$size\"></a>";
				}

				break;
		}

		return $lightbox_html;
	}

	/**
	 * Calculate taxes of goods
	 *
	 * @param      $amount           - Base amount
	 * @param bool $return_tax_amout - Taxe amount / Total amount
	 *
	 * @return string
	 */
	public static function calculateTaxe($amount, $return_tax_amout = false)
	{
		$tps = $amount * Setting::get('tps_rate');
		$tvq = $amount * Setting::get('tvq_rate');

		if ($return_tax_amout) {
			return number_format(round($tps + $tvq, 2, PHP_ROUND_HALF_EVEN), 2);
		} else {
			return number_format(round($amount + $tps + $tvq, 2, PHP_ROUND_HALF_EVEN), 2);
		}
	}


	/**
	 * Get array of date
	 *
	 * @param        $number_of_months
	 * @param string $format
	 *
	 * @return array
	 */
	public static function getLastXMonths($number_of_months, $format = "Y-m")
	{
		for ($i = 1; $i <= $number_of_months; $i++) {
			$months[] = date($format, strtotime(date('Y-m-01') . " -$i months"));
		}

		return $months;
	}

	/**
	 * Get link to query-product
	 *
	 * @param $product - Product to query.
	 */
	public static function getQueryLink($product, $style = "default", $size = "xs")
	{
		if (!empty($product)) {
			echo "<a class='btn btn-$style btn-$size' title='Voir la fiche du produit " . $product . "' data-toggle='tooltip' href='" . Path::root() . "/views/query-product.php?product=$product'>$product</a>";
		}
	}


	/**
	 * Highlight word in text
	 *
	 * @param $text   - Text to highlight
	 * @param $search - Word to search
	 *
	 * @return mixed
	 */
	public static function highlight($text, $search, $case_sensitive = false)
	{
		if ($case_sensitive) {
			foreach ($search as $keyword) {
				$data = preg_replace('/' . $keyword . '/', '<span style="padding: 2px 4px 2px 4px;" class="bg-primary">$0</span>', $text);
			}
		} else {
			foreach ($search as $keyword) {
				$data = preg_replace('/' . $keyword . '/i', '<span style="padding: 2px 4px 2px 4px;" class="bg-primary">$0</span>', $text);
			}
		}

		return $data;
	}

}