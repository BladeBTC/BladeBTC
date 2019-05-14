<?php

require $_SERVER['DOCUMENT_ROOT'] . '/gui/BladeBTC/Helpers/Loader.php';

use BladeBTC\GUI\Helpers\Mail;
use BladeBTC\GUI\Helpers\Path;
use BladeBTC\GUI\Helpers\Request;
use BladeBTC\GUI\Models\MailGroupMemberModel;
use BladeBTC\GUI\Models\MailGroupModel;
use BladeBTC\GUI\Models\Ogasys\AchReceptionEntModel;

/**
 * Data
 */
$mail_group_id = Request::get("mail_group_id");

/**
 * Handle for step 3 email selector
 */
if (is_numeric($mail_group_id)) {

	$mail_group_name = MailGroupModel::getGroupName($mail_group_id);
	$mail_group_email = MailGroupMemberModel::getAllMembersFromGroupID($mail_group_id, PDO::FETCH_ASSOC);

} else {
	$mail_group_name = "Gérant";
	$mail_address = explode(",", $mail_group_id);
	foreach ($mail_address as $email) {
		$mail_group_email[] = ["email" => $email];
	}
}

$br_serial = Request::get('br');
$br_number = AchReceptionEntModel::getReceptionBySerial($br_serial)->NoReception;
$ba_number = AchReceptionEntModel::getReceptionBySerial($br_serial)->NoBonAch;
$supplier = AchReceptionEntModel::getReceptionBySerial($br_serial)->NomFourn;
$supplier_num = AchReceptionEntModel::getReceptionBySerial($br_serial)->NoFourn;
$subject = "Le bon de réception " . $br_number . " doit être validé par le groupe " . $mail_group_name . ".";


/**
 * Prepare template parsing using Smarty
 */
$smarty = new Smarty();
$smarty->setTemplateDir($_SERVER['DOCUMENT_ROOT'] . '/gui/app/Email/Templates/');


/**
 * Template Data
 */
$smarty->assign('br_number', $br_number);
$smarty->assign('ba_number', $ba_number);
$smarty->assign('supplier', $supplier);
$smarty->assign('supplier_num', $supplier_num);
$smarty->assign('groupe', $mail_group_name);
$smarty->assign('url', Path::root() . "/views/tool-reception-validation.php?br=" . $br_serial);


/**
 * Parse this template
 */
$html = $smarty->fetch('ToolReceptionValidation.tpl');


/**
 * Generate PDF
 */
$pdf_name = uniqid('po_verification_', false) . ".pdf";
$pdf = file_get_contents(Path::pdf() . "/tool-reception-validation.php?save=1&fname=" . $pdf_name . "&br=" . $br_serial);


/**
 * Mail
 */
$mail_result = Mail::send($mail_group_email, $subject, $html, [[$_SERVER['DOCUMENT_ROOT'] . "/dist/img/add-header.gif", "banner.gif"]], ["path" => $_SERVER['DOCUMENT_ROOT'] . "/downloads/temp/" . $pdf_name, "name" => $pdf_name]);