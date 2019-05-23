<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/gui/BladeBTC/Helpers/Loader.php';

use BladeBTC\GUI\Helpers\Debugbar;
use BladeBTC\GUI\Helpers\Path;
use BladeBTC\GUI\Helpers\Security;
use BladeBTC\GUI\Helpers\Session;
use BladeBTC\GUI\Models\AccountModel;
use BladeBTC\GUI\Models\GroupModel;

/**
 * Validate access to the current page
 */
Security::validateAccess();

?>

    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>CMS | BladeBTC</title>
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
        <link rel="stylesheet" href="<?php echo Path::comp(); ?>/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="<?php echo Path::comp(); ?>/Ionicons/css/ionicons.min.css">
        <link rel="stylesheet" href="<?php echo Path::css(); ?>/base.css?v=<?php echo uniqid(); ?>">
        <link rel="stylesheet" href="<?php echo Path::css(); ?>/skin.css?v=<?php echo uniqid(); ?>">
        <link rel="stylesheet" href="<?php echo Path::css(); ?>/cms.css?v=<?php echo uniqid(); ?>">

        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Google Font -->
        <link rel="stylesheet"
              href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <script src="<?php echo Path::comp(); ?>/jquery/dist/jquery.min.js"></script>
        <script src="<?php echo Path::comp(); ?>/bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- DataTable -->
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.16/fh-3.1.3/r-2.2.1/sc-1.4.4/datatables.min.css"/>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.16/fh-3.1.3/r-2.2.1/sc-1.4.4/datatables.min.js"></script>


        <script src="<?php echo Path::js(); ?>/adminlte.min.js"></script>

        <!-- Alertify -->
        <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/alertify.min.js"></script>
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/alertify.min.css"/>
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.11.0/build/css/themes/bootstrap.min.css"/>

        <!-- Lightbox2 -->
        <script src="<?php echo Path::comp(); ?>/lightbox2/js/lightbox.js"></script>
        <link rel="stylesheet" href="<?php echo Path::comp(); ?>/lightbox2/css/lightbox.css">

        <!-- CKEDITOR -->
        <script type="text/javascript" src="<?php echo Path::comp(); ?>/ckeditor/ckeditor.js"></script>

        <!-- Date picker -->
        <script type="text/javascript"
                src="<?php echo Path::comp(); ?>/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
        <script type="text/javascript"
                src="<?php echo Path::comp(); ?>/bootstrap-datepicker/dist/locales/bootstrap-datepicker.fr.min.js"></script>
        <link rel="stylesheet"
              href="<?php echo Path::comp(); ?>/bootstrap-datepicker/dist/css/bootstrap-datepicker3.standalone.css">

        <!-- Pace -->
        <link rel="stylesheet" href="<?php echo Path::comp(); ?>/PACE/themes/red/pace-theme-loading-bar.css">
        <script data-pace-options='{ "startOnPageLoad": false }'
                src="<?php echo Path::comp(); ?>/PACE/pace.min.js"></script>

        <!-- Toast -->
        <link rel="stylesheet" href="<?php echo Path::comp(); ?>/jquery-toast/dist/jquery.toast.min.css">
        <script data-pace-options='{ "startOnPageLoad": false }'
                src="<?php echo Path::comp(); ?>/jquery-toast/dist/jquery.toast.min.js"></script>


        <!-- Debug Bar -->
		<?php
		if (Security::can(2)) {
			Debugbar::getHeaderHTML();
		}
		?>
    </head>
<body class="hold-transition skin-black sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="<?php echo Path::root(); ?>" class="logo">
            <span class="logo-mini"><img src="<?php echo Path::img(); ?>/logo-small.png" height="40"
                                         width="40"/></span>
            <span class="logo-lg"><img src="<?php echo Path::img(); ?>/logo-header.png"/></span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>


            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">


                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="<?php echo Path::profileImg(); ?>/<?php echo AccountModel::getProfileImg(Session::get("account_id")); ?>"
                                 class="user-image" alt="User Image"/>
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><?php
								echo AccountModel::getFullName(Session::get("account_id")) ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="<?php echo Path::profileImg(); ?>/<?php echo AccountModel::getProfileImg(Session::get("account_id")); ?>"
                                     class="img-circle" alt="User Image"/>
                                <p>
									<?php
									echo AccountModel::getFullName(Session::get("account_id")); ?>
                                    <small>
										<?php echo GroupModel::getNameById(AccountModel::getAccountGroup(Session::get("account_id"))); ?>
                                        <br/>(ID : <?php echo Session::get("account_id"); ?>)
                                    </small>
                                </p>
                            </li>
                            <!-- Menu Body -->

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-left">
                                    <a href="<?php echo Path::root(); ?>/views/profile.php"
                                       class="btn btn-default">My account</a>
                                </div>
                                <div class="pull-right">
                                    <a href="<?php echo Path::root(); ?>/logout.php"
                                       class="btn btn-danger">Logout</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>

<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/views/partials/menu.php';