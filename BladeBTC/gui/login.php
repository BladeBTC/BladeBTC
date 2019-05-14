<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/BladeBTC/Helpers/Loader.php';

use BladeBTC\GUI\Controllers\Login;
use BladeBTC\GUI\Helpers\Messages;
use BladeBTC\GUI\Helpers\Path;
use BladeBTC\GUI\Helpers\Request;
use BladeBTC\GUI\Helpers\Session;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title> CMS - Connexion
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <link rel="icon" type="image/png" href="<?php echo Path::img(); ?>/favicon.png">
    <link rel="shortcut icon" type="image/x-icon" href="<?php echo Path::img(); ?>/favicon.ico">
    <link href="<?php echo Path::comp(); ?>/bootstrap/dist/css/bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet"
          type="text/css"/>
    <link href="<?php echo Path::css(); ?>/AdminLTE.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo Path::css(); ?>/skins/skin-red.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo Path::css(); ?>/cms.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo Path::comp(); ?>/iCheck/all.css" rel="stylesheet" type="text/css"/>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<link href="<?php echo Path::css(); ?>/auth.css" rel="stylesheet" type="text/css"/>

<div class="container">
    <div id="loginbox" class="mainbox col-md-6 col-md-offset-3 col-sm-6 col-sm-offset-3">
        <div class="row">
            <div class="iconmelon"><img class="img-responsive" src="<?php echo Path::img(); ?>/logo.png"
                                        alt="Logo">
            </div>
        </div>
        <div class="panel panel-default box-shadow">
            <div class="panel-body">

                <div class="row">
                    <div class="col-md-12">
						<?php
						if (!is_null(Request::post('login')) && Session::getFormId('login') == Request::post('DBLP')) {
							try {
								Login::login();
							} catch (Exception $e) {
								Messages::error($e->getMessage());
							}
						}
						?>
                    </div>
                </div>

                <form action="<?php echo Path::root(); ?>/login.php" method="post">
                    <input type="hidden" name="DBLP" value="<?php echo Session::setFormId('login'); ?>">
                    <div class="form-group has-feedback">
                        <input type="text" class="form-control" placeholder="Nom d'utilisateur" name="username"/>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" placeholder="Mot de passe" name="password"/>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="checkbox icheck">
                                <label>
                                    <input type="checkbox" name="remember" checked> Rester connecté
                                </label>
                            </div>
                        </div><!-- /.col -->
                        <div class="col-md-6">
                            <input type="submit" class="btn btn-danger btn-block btn-flat" value="Connexion"
                                   name="login"/>
                        </div><!-- /.col -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="particles"></div>
<script src="<?php echo Path::comp(); ?>/jquery/dist/jquery.min.js"></script>
<script src="<?php echo Path::comp(); ?>/bootstrap/dist/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?php echo Path::comp(); ?>/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="<?php echo Path::js(); ?>/particuleground.js"></script>
<script src="<?php echo Path::js(); ?>/cms.js"></script>
<script>
    $(function () {
        $('input').iCheck({
            checkboxClass: 'icheckbox_square-red',
            radioClass: 'iradio_square-red'
        });

        $('#particles').particleground({
            minSpeedX: 0.3,
            maxSpeedX: 0.7,
            minSpeedY: 0.3,
            maxSpeedY: 0.7,
            directionX: 'center',
            directionY: 'center',
            density: 8000,
            dotColor: '#ddd',
            lineColor: '#ddd',
            particleRadius: 7,
            lineWidth: 1,
            curvedLines: false,
            proximity: 100,
            parallax: true
        });
    });
</script>
</body>
</html>