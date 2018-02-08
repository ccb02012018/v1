<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/20/18
 * Time: 9:36 PM
 */

namespace common\models\utils;

class DateUtil
{
    /**
     * @return integer
     */
    public static function getLocalTime()
    {
        $fecha = date_create();
        return date_format($fecha, 'U');
    }

    public static function getDateTime() {
        $fecha = date_create();
        return date_format($fecha, 'Y-m-d H:i:s');
    }

}