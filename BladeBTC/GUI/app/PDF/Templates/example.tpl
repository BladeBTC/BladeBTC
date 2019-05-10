<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste de vérification</title>
    <style type="text/css">
        @font-face {
            font-family: 'HelveticaNeueMedium';
            src: url('{$root}/dist/fonts/HelveticaNeue-Medium.ttf') format('truetype');
        }

        body, html, p {
            margin: 0;
            padding: 0;
            font-family: HelveticaNeueMedium, Arial, sans-serif;
            font-size: 11px;
        }

        #po {
            margin: 2% 2% 2% 2%;
        }

        #po table {
            border-collapse: collapse;
            border-spacing: 0;
            border: none;
        }

        #po table td {
            padding: 2px;
            border: 1px solid #000;
        }
    </style>
</head>
<body>
<div id="po">
    <!-- HEADER -->
    <table style="width:100%;">
        <tr>
            <td rowspan="2" style="text-align: left; width:25%; border: 0;"><img src="../../dist/img/logo.png"
                                                                                 width="206" height="84"></td>
            <td style="text-align: center; width:40%; border: 0;"><p>
                    8018, 20<sup>e</sup> Avenue, Montréal, QC, CA, H1Z 3S7<br>
                    Tél : (514) 376-1740 - Fax : (514) 376-9792</p></td>
            <td style="text-align: right; width:25%; border: 0;"></td>
        </tr>
        <tr>
            <td style="text-align: center; border: 0;"><p>www.website.com</p></td>
            <td style="border: 0;">
                <table style="width:100%;">
                    <tr>
                        <td style="text-align: center; background-color:#EEEEEE;">{$dtitle}</td>
                    </tr>
                    <tr>
                        <td style="text-align: center;">{$dnumber}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!-- HEADER -->
    <br>
    <!-- ADDRESS -->
    <table style="width:100%;">
        <tr>
            <td style="width:50%; background-color:#EEEEEE;">Commandé de</td>
            <td style="width:50%; background-color:#EEEEEE;">Livré à</td>
        </tr>
        <tr>
            <td>{$vname}<br>
                {$vaddress}<br>
                {$vcity}, {$vstate}, {$vpostal}<br>
                Tél : {$vphone} Fax: {$vfax}</td>
            <td>{$cname}<br>
                {$caddress}<br>
                {$ccity}, {$cstate}, {$cpostal}</td>
        </tr>
    </table>
    <!-- //ADDRESS -->

    <!-- FOURNISSEUR -->
    <table style="width:100%; margin-top:5px">
        <tr>
            <td style="text-align: center; background-color:#EEEEEE;">Fournisseur</td>
            <td style="text-align: center; background-color:#EEEEEE;">Référence</td>
            <td style="text-align: center; background-color:#EEEEEE;">Commandée</td>
            <td style="text-align: center; background-color:#EEEEEE;">Date requise</td>
            <td style="text-align: center; background-color:#EEEEEE;">Annulation</td>
            <td style="text-align: center; background-color:#EEEEEE;">Commis</td>
        </tr>
        <tr>
            <td style="text-align: center">{$po_supplier}</td>
            <td style="text-align: center"></td>
            <td style="text-align: center">{$po_date}</td>
            <td style="text-align: center">{$po_require_date}</td>
            <td style="text-align: center"></td>
            <td style="text-align: center">{$po_by}</td>
        </tr>
    </table>
    <!-- //FOURNISSEUR -->

    <!-- PRODUIT -->
    <table style="width:100%; margin-top:5px; font-size:10px;">
        <tr>
            <td style="text-align: center; background-color:#EEEEEE;">Produit</td>
            <td style="text-align: center; background-color:#EEEEEE;">Description</td>
            <td style="text-align: center; background-color:#EEEEEE;">Commandée</td>
            <td style="text-align: center; background-color:#EEEEEE;">Reçu</td>
            <td style="text-align: center; background-color:#EEEEEE;">Coût bon d'achat</td>
            <td style="text-align: center; background-color:#EEEEEE;">Coût moyen</td>
            <td style="text-align: center; background-color:#EEEEEE;">Coût provisionné</td>
            <td style="text-align: center; background-color:#EEEEEE;">Vendant</td>
            <td style="text-align: center; background-color:#EEEEEE;">Éco.</td>
            <td style="text-align: center; background-color:#EEEEEE;">Épaisseur</td>
            <td style="text-align: center; background-color:#EEEEEE;">Largeur</td>
            <td style="text-align: center; background-color:#EEEEEE;">Longeur</td>
            <td style="text-align: center; background-color:#EEEEEE;">Poids</td>
            <td style="text-align: center; background-color:#EEEEEE;">Volume</td>
            <td style="text-align: center; background-color:#EEEEEE;">CUP</td>
            <td style="text-align: center; background-color:#EEEEEE;">Master</td>
            <td style="text-align: center; background-color:#EEEEEE;">Inner</td>
            <td style="text-align: center; background-color:#EEEEEE;">Plancher</td>
            <td style="text-align: center; background-color:#EEEEEE;">Plancher Qtée.</td>
            <td style="text-align: center; background-color:#EEEEEE;">Magento</td>
            <td style="text-align: center; background-color:#EEEEEE;">Photo</td>
        </tr>


        {foreach $po_items as $item}
            <tr>
                <td style="text-align: center;"><a href="{$root}/views/query-product.php?product={$item.produit}"
                                                   target="_blank">{$item.produit}</a></td>
                <td style="text-align: center;">{$item.description}</td>
                <td style="text-align: center;">{$item.commandee}</td>
                <td style="text-align: center;">{$item.recue}</td>
                <td style="text-align: center;">{$item.cout_us}</td>
                <td style="text-align: center;">{$item.cout_cad}</td>
                <td style="text-align: center; background-color: green; color: white;">{$item.cout_prov}</td>
                <td style="text-align: center;">{$item.prix}</td>
                <td style="text-align: center;">{$item.eco} / {$item.eco_desc}</td>
                <td style="text-align: center;">{$item.epaiseur} cm</td>
                <td style="text-align: center;">{$item.largeur} cm</td>
                <td style="text-align: center;">{$item.longeur} cm</td>
                <td style="text-align: center;">{$item.poids} kg</td>
                <td style="text-align: center;">{$item.volume} cm<sup>3</sup></td>
                <td style="text-align: center;">{$item.upc}</td>
                <td style="text-align: center;">{$item.master}</td>
                <td style="text-align: center;">{$item.inner}</td>
                <td style="text-align: center;">{$item.plancher}</td>
                <td style="text-align: center;">{$item.plancher_qte}</td>
                <td style="text-align: center;">{$item.web}</td>
                <td style="text-align: center;"><img src="{$item.img}"
                                                     width="75" height="75"
                                                     alt="Image {$item.produit}"></td>
            </tr>
        {/foreach}
    </table>
    <!-- //PRODUIT -->

    <!-- PROVISION -->
    <table style="width:40%; margin-top:5px; font-size: 12px">
        <tr>
            <td style="text-align: center; background-color:#EEEEEE;">Code</td>
            <td style="text-align: center; background-color:#EEEEEE;">Description</td>
            <td style="text-align: center; background-color:#EEEEEE;">Montant total</td>
        </tr>

        {foreach $provision as $line}
            <tr>
                <td style="text-align: center;">{$line.TypeCT}</td>
                <td style="text-align: left;">{$line.Description}</td>
                <td style="text-align: center;">{$line.Montant}</td>
            </tr>
        {/foreach}
    </table>
    <!-- //PROVISION -->


    <!-- PIED DE PAGE -->
    <table style="width:100%; margin-top:5px;">
        <tr>
            <td style="text-align: left; border: 0;">{$date}</td>
            <td style="text-align: center; border: 0;"></td>
            <td style="text-align: right; border: 0;">&nbsp;</td>
        </tr>
    </table>
    <!-- //PIED DE PAGE -->
</div>
</body>
</html>
