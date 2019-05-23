<?php

namespace BladeBTC\GUI\Helpers;

use upload as UploadClass;

class Upload
{
    /**
     * Validate and upload an profile image
     *
     * @param $file_object - Image
     * @param $path        - Where to upload image
     *
     * @return array
     */
	public static function profile_image($file_object, $path)
	{
		$handle = new UploadClass($file_object, 'fr_FR');
		if ($handle->uploaded) {
			$handle->file_max_size = '4M';
			$handle->file_safe_name = true;
			$handle->file_name_body_pre = 'profile_';
			$handle->file_auto_rename = true;
			$handle->dir_auto_create = false;
			$handle->mime_check = true;
			$handle->allowed = ['image/*'];
			$handle->image_convert = 'png';
			$handle->png_compression = 5;
			$handle->image_interlace = true;
			$handle->process($path);
			if ($handle->processed) {

				$data = [
					"msg"      => $handle->file_dst_name,
					"uploaded" => true,
				];

				$handle->clean();

				return $data;
			} else {

				$data = [
					"msg"      => $handle->error,
					"uploaded" => false,
				];

				return $data;
			}
		}

		$data = [
			"msg"      => "Unknown error",
			"uploaded" => false,
		];

		return $data;
	}


	/**
	 * Upload label image.
	 *
	 * @param $file_object - Image
	 * @param $name        - New name
	 * @param $path        - Store path
	 *
	 * @return array
	 */
	public static function style_label_image($file_object, $name, $path)
	{
		$handle = new UploadClass($file_object, 'fr_FR');
		if ($handle->uploaded) {
			$handle->file_max_size = '4M';
			$handle->file_new_name_body = $name;
			$handle->file_auto_rename = false;
			$handle->dir_auto_create = false;
			$handle->process($path);
			if ($handle->processed) {

				$data = [
					"msg"      => $handle->file_dst_name,
					"uploaded" => true,
				];

				$handle->clean();

				return $data;
			} else {

				$data = [
					"msg"      => $handle->error,
					"uploaded" => false,
				];

				return $data;
			}
		}

		$data = [
			"msg"      => "Unknown error",
			"uploaded" => false,
		];

		return $data;
	}
}