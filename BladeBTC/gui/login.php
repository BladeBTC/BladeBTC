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
    <title> BladeBTC - Login
    </title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="apple-touch-icon" sizes="57x57" href="<?php echo Path::img(); ?>/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php echo Path::img(); ?>/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php echo Path::img(); ?>/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php echo Path::img(); ?>/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php echo Path::img(); ?>/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php echo Path::img(); ?>/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php echo Path::img(); ?>/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php echo Path::img(); ?>/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo Path::img(); ?>/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="<?php echo Path::img(); ?>/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo Path::img(); ?>/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php echo Path::img(); ?>/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo Path::img(); ?>/favicon-16x16.png">
    <link rel="manifest" href="<?php echo Path::img(); ?>/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="<?php echo Path::img(); ?>/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="<?php echo Path::comp(); ?>/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo Path::css(); ?>/base.css">
    <link rel="stylesheet" href="<?php echo Path::css(); ?>/skin.css">
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
                        <input type="text" class="form-control" placeholder="Username" name="username"/>
                        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                    </div>
                    <div class="form-group has-feedback">
                        <input type="password" class="form-control" placeholder="Password" name="password"/>
                        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <input type="submit" class="btn bg-orange btn-block"" value="Login"
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