<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 2/5/18
 * Time: 3:58 PM
 */

namespace services;

use bots\request\Request;

class TimeService extends Request
{
    public static function localTime () {
        return self::request('time/local-time');
    }
    public static function serverTime ($bot_id) {
        return self::request('time/server-time', ['bot_id' => $bot_id]);
    }
}