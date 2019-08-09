<?php


namespace BladeBTC\Helpers;


class Curl
{

    /**
     * Get json data from an URL
     *
     * @param      $url
     *
     * @param bool $fetch_assoc
     *
     * @return array|mixed
     */
    public static function get($url, $fetch_assoc = false)
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

        /**
         * Log request/response
         */
        if (getenv('DEBUG') == 1){

            error_log("[CURL REQUEST]: " . $url, 0);
            error_log("[CURL RESPONSE]: " . $json, 0);
        }

        if ($fetch_assoc){
            return json_decode($json, true);
        }

        return json_decode($json);
    }

    /**
     * Get raw Data from an URL
     *
     * @param $url
     *
     * @return array|bool|string
     */
    public static function getRaw($url)
    {

        $ch = curl_init();

        if (!$ch) {
            return ["error" => "Couldn't initialize a cURL handle"];
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        $raw = curl_exec($ch);
        curl_close($ch);

        /**
         * Log request/response
         */
        if (getenv('DEBUG') == 1){

            error_log("[CURL REQUEST]: " . $url, 0);
            error_log("[CURL RESPONSE]: " . $raw, 0);
        }

        return $raw;
    }
}