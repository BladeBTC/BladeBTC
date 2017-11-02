<?php
/**
 * Created by PhpStorm.
 * User: ylafontaine
 * Date: 08/06/2017
 * Time: 16:51
 */

namespace BladeBTC\Helpers;

use Telegram\Bot\Api;

class WebHook
{
    public static function set(Api $telegram, $url)
    {
        $telegram->setWebhook(['url' => $url]);
    }
}