<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 2/5/18
 * Time: 4:03 PM
 */

namespace bots\request;

use common\models\ApiResult;

class Request
{
    const BASE = 'http://localhost/CryptoBot/v1/api/web/';

    public static function request($url, $data = [], $method = 'GET')
    {
        try {
            $options = array(
                'http' => array(
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                    'method' => $method,
                    'content' => http_build_query($data)
                )
            );
            $context = stream_context_create($options);
            $result = file_get_contents(self::BASE . $url, false, $context);

            $response = new ApiResult();
            $response->setResponse(json_decode($result, true));

            if ($response->result === false || $response->error != -1) {
                return false;
            }

            return ($response->result);
        } catch (\Exception $exception) {
            var_dump('zxczxczxc');
        }
    }


    public static function request2($url, $params = [], $method)
    {
        $opt = [
            "http" => [
                "method" => $method,
                "ignore_errors" => true,
                "header" => "User-Agent: Mozilla/4.0 (compatible; PHP Env Bots API)\r\n",
            ],
        ];
        $context = stream_context_create($opt);
        $query = http_build_query($params, '', '&');
        echo self::BASE . $url . '?' . $query;

        return json_decode(file_get_contents(self::BASE . $url . '?' . $query, false, $context), true);
    }
}