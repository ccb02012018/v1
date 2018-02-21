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
            var_dump($url . '?' . json_encode($data));
            $options = array(
                'http' => array(
                    'header' => "Content-type: application/x-www-form-urlencoded\r\n" . 'Cache-Control: no-cache' . "\r\n",
                    'method' => $method,
                    'content' => http_build_query($data)
                )
            );
            $context = stream_context_create($options);
            $result = file_get_contents(self::BASE . $url, false, $context);

            $response = new ApiResult();
            $response->setResponse(json_decode($result, true));

            if ($response->result === false || $response->error != -1) {
                var_dump('Error: ' . $response->errorMessage);
                return false;
            }

            return ($response->result);
        } catch (\Exception $exception) {
            var_dump('kuek!');
        }
    }


    public static function requestGet($url, $params = [], $method = 'GET')
    {
        try {
            var_dump($url);
            $opt = [
                "http" => [
                    "method" => $method,
                    "ignore_errors" => true,
                    "header" => "User-Agent: Mozilla/4.0 (compatible; PHP Env Bots API)\r\n" . 'Cache-Control: no-cache' . "\r\n",
                ],
            ];
            $context = stream_context_create($opt);
            $query = http_build_query($params, '', '&');

            $result = file_get_contents(self::BASE . $url . '?' . $query, false, $context);

            $response = new ApiResult();
            $response->setResponse(json_decode($result, true));

            if ($response->result === false || $response->error != -1) {
                var_dump('Error: ' . $response->errorMessage);
                return false;
            }

            return ($response->result);
        } catch (\Exception $exception) {
            var_dump('kuek!');
        }
    }
}