<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 2017-10-10
 * Time: 13:47
 */

use App\Helpers\Debugbar;
use App\Helpers\Path;
use App\Helpers\Security;
use App\Helpers\Toast;
?>

<!-- /.content-wrapper -->

<!-- Main Footer -->
<footer class="main-footer">
    <!-- Default to the left -->
    <strong>Copyright &copy; 2014 - 2017 <a href="<?php echo Path::root(); ?>">CMS</a></strong> -
    Tous les droits sont réservés.

	<?php
	if (Security::can(1)) {
		?>
        <div class="pull-right hidden-xs">
			<?php
			echo "\n Généré en <strong>" . number_format(microtime(true) - $start_time, 2) . "</strong> secondes.";
			?>
        </div>
	<?php } ?>
</footer>


</div>
<!-- ./wrapper -->
<script src="<?php echo Path::comp(); ?>/moment/moment.js"></script>
<link href="<?php echo Path::comp(); ?>/iCheck/all.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo Path::comp(); ?>/iCheck/icheck.min.js" type="text/javascript"></script>
<script src="<?php echo Path::js(); ?>/cms.js"></script>

<!-- Date range picker -->
<script type="text/javascript" src="<?php echo Path::comp(); ?>/daterangepicker/daterangepicker.js"></script>
<link rel="stylesheet" href="<?php echo Path::comp(); ?>/daterangepicker/daterangepicker-bs3.css">


<!-- Color picker -->
<script type="text/javascript" src="<?php echo Path::comp(); ?>/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js"></script>
<link rel="stylesheet" href="<?php echo Path::comp(); ?>/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css">


<!-- Select -->
<link rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.4/js/bootstrap-select.min.js"></script>



<!-- Debug Bar -->
<?php
if (Security::can(3)) {
	Debugbar::getBodyHTML();
}

/**
 * Display Stored Toast Message
 */
Toast::display();

?>


</body>
</html>
