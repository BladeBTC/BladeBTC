<?php

require 'vendor/autoload.php';

use Telegram\Bot\Api;

$telegram = new Api('384533803:AAE1pyxwEVQVZ_ayHc3glmoWZ4_GwtJCZK4');
// Standalone
$response = $telegram->setWebhook(['url' => 'https://test.aeclient.com/webHookHandler.php']);
