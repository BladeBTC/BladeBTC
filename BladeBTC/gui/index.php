<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/BladeBTC/Helpers/Loader.php';

use BladeBTC\GUI\Controllers\Login;
use BladeBTC\GUI\Helpers\Security;

/**
 * Validate access to the current page
 */
Security::validateAccess();


/**
 * Redirect to dashboard if user is logged in.
 */
Login::redirect();

