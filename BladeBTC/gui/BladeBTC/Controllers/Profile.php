<?php

namespace BladeBTC\GUI\Controllers;

use BladeBTC\GUI\Helpers\Password;
use BladeBTC\GUI\Helpers\Request;
use BladeBTC\GUI\Helpers\Session;
use BladeBTC\GUI\Helpers\Upload;
use BladeBTC\GUI\Models\AccountModel;
use Exception;

class Profile
{


    public static function update()
    {
        /**
         * Prepare password
         */
        $pwd = Request::post('pwd');
        $pwd_hash = Password::hash($pwd);

        /**
         * Prepare image
         */
        $img = Request::file('img');
        if (!empty($img['name'])) {

            $img_result = Upload::profile_image($img, $_SERVER["DOCUMENT_ROOT"] . '/dist/img/profiles/');
            if ($img_result["uploaded"]) {
                $img_path = $img_result["msg"];
            }
            else {
                throw new Exception("Une erreur s'est produit avec le traitement de votre photo : " . $img_result["msg"]);
            }
        }

        try {

            if (!empty($pwd)) {
                AccountModel::setPassword(Session::get('account_id'), $pwd_hash);
            }

            if (!empty($img['name'])) {
                AccountModel::setProfileImg(Session::get('account_id'), $img_path);
            }

            return true;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}