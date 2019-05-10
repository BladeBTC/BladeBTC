<?php
/**
 * Copyright (C) 2014 - 2017 CMS - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary  and confidential
 * Last edit : 17-02-08 16:19
 */

require $_SERVER['DOCUMENT_ROOT'] . '/app/Helpers/Loader.php';

use App\Helpers\Path;
use App\Helpers\Request;
use App\Helpers\Security;
use App\Helpers\Utils;
use Dompdf\Dompdf;

/**
 * Validate access
 */
if (Request::get("save") != 1) {
	Security::havePdfAccess();
}

/**
 * Data
 */
$br_serial = Request::get("br");
$header = AchReceptionEntModel::getReceptionBySerial($br_serial);
$detail = AchReceptionDetModel::getReceptionBySerial($br_serial);

/**
 * Prepare template parsing using Smarty
 */
$smarty = new Smarty();
$smarty->setTemplateDir($_SERVER['DOCUMENT_ROOT'] . '/app/PDF/Templates/');

//PREPARE ITEMS
$products = null;
foreach ($detail as $row) {

	$p = new Product($row->Produit);

	//SKIP SAMPLES AND PACKAGING
	if (substr($row->Produit, 0, 3) == "ZEC") {
		continue;
	} elseif (strtoupper($row->Produit) == "PACKAGING") {
		continue;
	} elseif (str_contains(strtoupper($row->Produit), "TRANS")) {
		continue;
	}elseif (trim(strtoupper($p->getCodeCatMaj())) == "GL"){
		continue;
	}



	if (file_exists($_SERVER['DOCUMENT_ROOT'] . 'dist/img/products/' . strtoupper($p->getProduct()) . '.jpg')) {
		$img = '../../dist/img/products/' . strtoupper($p->getProduct()) . '.jpg';
	} else {
		$img = '../../dist/img/no_image.jpg';
	}

	$products[] = [
		"produit"      => $p->getProduct(),
		"description"  => $p->getDescription(),
		"marque"       => $p->getMarque(),
		"model"        => $p->getModel(),
		"commandee"    => Utils::nf($row->CmQteCommandee, 0),
		"recue"        => Utils::nf($row->CmQteRecue, 0),
		"cout_us"      => Utils::nf($row->CmPrixUnitaire),
		"cout_cad"     => Utils::nf($p->getAverageCost(), 3),
		"prix"         => $p->getPrice(),
		"eco"          => $p->getEhfGroupCode(),
		"eco_desc"     => $p->getEhfGroupDescription(),
		"epaiseur"     => Utils::nf($p->getEpaisseur()),
		"largeur"      => Utils::nf($p->getLargeur()),
		"longeur"      => Utils::nf($p->getLongueurTaille()),
		"poids"        => Utils::nf($p->getPoids()),
		"volume"       => Utils::nf($p->getVolume()),
		"upc"          => $p->getUpc(),
		"cat_maj"      => $p->getDescCat(),
		"cat_int"      => $p->getDescCatInt(),
		"cat_min"      => $p->getDescCatMin(),
		"sugg_prod"    => $p->getSuggestedProduct(),
		"master"       => $p->getMasterPack(),
		"inner"        => $p->getInnerPack(),
		"plancher"     => $p->getInStore() == true ? "Oui" : "Non",
		"plancher_qte" => Utils::nf($p->getInStoreQuantity(), 0),
		"web"          => "N/D",
		"img"          => $img,
	];
}

/**
 * Assign value to this template
 */

//CSS
$smarty->assign('root', Path::root());

//DOCUMENT TITLE + NUMBER
$smarty->assign('dtitle', 'Bon de réception');
$smarty->assign('dnumber', $header->NoReception);

//SUPPLIER
$smarty->assign('vname', $header->NomFourn);
$smarty->assign('vaddress', $header->AdresseFourn1);
$smarty->assign('vcity', $header->AdresseFourn2);
$smarty->assign('vstate', $header->AdresseFourn3);
$smarty->assign('vpostal', $header->CodePostalFourn);

//PO
$smarty->assign('po_supplier', $header->NoFourn);
$smarty->assign('po_date', Utils::dateFromTimeStamp('d/m/Y', $header->DateRec));

//ITEMS
$smarty->assign('po_items', $products);

//FOOTER
$smarty->assign('date', date('d/m/Y H:m:s'));

/**
 * Parse this template
 */
$html = $smarty->fetch('tool-reception-validation.tpl');

/**
 * Generate pdf form html and open in browser
 */
if (Request::get("save") != 1) {
	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('legal', 'landscape');
	$dompdf->render();
	$dompdf->stream(uniqid('po_verification_', true), ['Attachment' => 0]);
} else {
	$dompdf = new Dompdf();
	$dompdf->loadHtml($html);
	$dompdf->setPaper('legal', 'landscape');
	$dompdf->render();
	$pdf = $dompdf->output();
	file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/downloads/temp/' . Request::get("fname"), $pdf);
}
?>