<?php
require $_SERVER['DOCUMENT_ROOT'] . '/gui/BladeBTC/Helpers/Loader.php';

use BladeBTC\GUI\Controllers\Login;
use BladeBTC\GUI\Helpers\Security;
use BladeBTC\GUI\Models\ErrorLogs;

/**
 * Fatal error log
 */
register_shutdown_function(function () {
    $error = error_get_last();

    if ($error !== null) {
        $errno = $error["type"];
        $errfile = $error["file"];
        $errline = $error["line"];
        $errstr = $error["message"];

        ErrorLogs::Log($errno, $errstr, $errline, 'GUI', $errfile);
    }
});


/**
 * Validate access to the current page
 */
Security::validateAccess();


/**
 * Redirect to dashboard if user is logged in.
 */
Login::redirect();

