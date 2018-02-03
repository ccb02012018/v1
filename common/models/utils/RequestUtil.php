<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/30/18
 * Time: 12:56 PM
 */

namespace common\models;


class RequestUtil
{
    static public $base = 'https://api.binance.com/api/';

    public static function request ($url, $params = [], $method = "GET") {
        $opt = [
            "http" => [
                "method"        => $method,
                "ignore_errors" => true,
                "header"        => "User-Agent: Mozilla/4.0 (compatible; PHP Binance API)\r\n",
            ],
        ];
        $context = stream_context_create($opt);
        $query = http_build_query($params, '', '&');

        return json_decode(file_get_contents(static::$base . $url . '?' . $query, false, $context), true);
    }

}