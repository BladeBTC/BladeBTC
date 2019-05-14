<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html charset=UTF-8"/>
</head>
<body style="text-align: center;">
<table width="600">
    <tr>
        <td style="font-size: 12px; color: #333; font-family: 'Lucida Sans Unicode', 'Lucida Grande', sans-serif; border:1px solid #ccc; padding:10px;">
            <img src="cid:banner.gif" width="600" height="160"/>
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="padding:10px; font-size: 12px; color: #333; font-family: 'Lucida Sans Unicode', 'Lucida Grande', sans-serif; border:1px solid #ccc;">
                        <span style="font-size:20px; font-weight:bold;">Validation de produit</span><br/>
                        <br/>
                        <table cellspacing="0" cellpadding="5" border="1">
                            <tr>
                                <th>Bon de réception</th>
                                <td>{$br_number}</td>
                            </tr>
                            <tr>
                                <th>Bon d'achat</th>
                                <td>{$ba_number}</td>
                            </tr>
                            <tr>
                                <th>Fournisseur</th>
                                <td>{$supplier_num} - {$supplier}</td>
                            </tr>
                        </table>
                        <br/>
                        <p style="margin:0;">Ce message vous a été envoyé depuis l'intranet 2. <br/>Une liste de
                            vérification
                            de produit est à être validé par un membre du groupe <strong>{$groupe}</strong>.<br/> Votre
                            courriel fait partie
                            de la liste de distribution pour le groupe {$groupe}. <br/><br/>Afin de valider la liste de
                            produits
                            vous pouvez appuyer sur le lien <strong>Débuter la validation des produits</strong> pour
                            consulter les produits contenus dans cette
                            liste.</p>
                        <br/>
                        <a href="{$url}"><strong>Débuter la validation des produits</strong></a>
                        <br/>
                        <br/>
                        <em> L'&eacute;quipe!</em></td>
                </tr>
                <tr>
                    <td style="font-size: 12px; color: grey; font-family: 'Lucida Sans Unicode', 'Lucida Grande', sans-serif;">
                        <div align="center"><br/>
                            <hr style="background-color:grey; border:0; height:1px;">
                            8018, 20E Avenue, Montreal, QC H1Z 3S7 <br/>
                            8018, 20E Avenue, Montreal, QC H1Z 3S7 <br/>
                            514 376-1740
                        </div>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
