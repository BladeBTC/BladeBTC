<?php


namespace BladeBTC\Helpers;


class Curl
{
    public static function get($url)
    {

        $ch = curl_init();

        if (!$ch) {
            return ["error" => "Couldn't initialize a cURL handle"];
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $json = curl_exec($ch);
        curl_close($ch);

        return json_decode($json);
    }
}