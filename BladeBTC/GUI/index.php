<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 2017-10-10
 * Time: 13:47
 */

require $_SERVER['DOCUMENT_ROOT'] . '/app/Helpers/Loader.php';

use App\Controllers\Login;
use App\Helpers\Security;

/**
 * Validate access to the current page
 */
Security::validateAccess();


/**
 * Redirect to dashboard if user is logged in.
 */
Login::redirect();

?>