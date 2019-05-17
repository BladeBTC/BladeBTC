<?php

namespace BladeBTC\GUI\Controllers;

use BladeBTC\GUI\Helpers\Request;
use BladeBTC\GUI\Models\ErrorLogs;
use Exception;

class ManageErrorLog
{

    /**
     * Handle multiples actions in Error Log management.
     *
     * @return null|string
     * @throws Exception
     */
    public static function action()
    {

        $action = Request::get('action');

        $msg = null;
        switch ($action) {


            case "delete":

                ErrorLogs::delete(Request::get('id'));

                $msg = "The error line has been deleted.";

                break;


            case "clear":

                ErrorLogs::clear();

                $msg = "The error logs has been deleted.";

                break;

        }

        return $msg;
    }
}

