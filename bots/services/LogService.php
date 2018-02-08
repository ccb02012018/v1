<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 2/5/18
 * Time: 3:58 PM
 */

namespace services;

use bots\request\Request;

class LogService extends Request
{
    public static function writeSimpleLog($log)
    {
        return self::request('logs', ['log_message' => $log], 'POST');
    }
}