<?php

namespace BladeBTC\Helpers;

use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class WebHook
{

    /**
     * Set Web Hook
     * @param Api $telegram
     * @param $url
     * @throws TelegramSDKException
     */
    public static function set(Api $telegram, $url)
    {
        $telegram->setWebhook(['url' => $url]);
    }

    /**
     * Remove Bot Web Hook
     * @param Api $telegram
     */
    public static function remove(Api $telegram)
    {
        $telegram->removeWebhook();
    }
}