<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 2018-06-04
 * Time: 11:45
 */

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;

class Mail
{

	/**
	 * Send an email
	 *
	 * @param array $to        - List of email address and name
	 * @param       $subject   - Email subject
	 * @param       $html_body - Email body
	 * @param       $img_embed - Image to embed in message
	 * @param 		$attachment - Attachment
	 *
	 * @return array
	 */
	public static function send(array $to, $subject, $html_body, array $img_embed = [], array $attachment = null)
	{
		try {
			$mail = new PHPMailer();
			$mail->isSMTP();
			$mail->Host = getenv("SMTP_HOST");
			$mail->Port = getenv("SMTP_PORT");
			$mail->CharSet = 'UTF-8';
			$mail->setFrom('no-reply@' . getenv("EMAIL_DOMAIN"), getenv("EMAIL_NAME"));

			foreach ($to as $email) {
				$mail->addAddress($email['email'], isset($email['name']) ? $email['name'] : null);
			}

			$mail->Subject = $subject;

			foreach ($img_embed as $img_attachment){
				$mail->AddEmbeddedImage($img_attachment[0], $img_attachment[1]);
			}

			$mail->msgHTML($html_body);

			if (!is_null($attachment)){
				$mail->addAttachment($attachment["path"], $attachment["name"]);
			}

			if (!$mail->send()) {

				$data = [
					"errorlevel" => 1,
					"msg"        => $mail->ErrorInfo,
				];

			} else {

				$data = [
					"errorlevel" => 0,
					"msg"        => "OK",
				];

			}

			return $data;
		} catch (\Exception $e) {
			$data = [
				"errorlevel" => 1,
				"msg"        => $e->getMessage(),
			];

			return $data;
		}
	}
}