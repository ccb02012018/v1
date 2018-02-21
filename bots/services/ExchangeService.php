<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 2/5/18
 * Time: 3:58 PM
 */

namespace services;

use bots\request\Request;

class ExchangeService extends Request
{
    public static function taked($exc_id)
    {
        return self::request('exchange/taked', ['exc_id' => $exc_id], 'POST');
    }
    public static function untaked($exc_id)
    {
        return self::request('exchange/untaked', ['exc_id' => $exc_id], 'POST');
    }
}