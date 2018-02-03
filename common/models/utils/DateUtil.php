<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/20/18
 * Time: 9:36 PM
 */

namespace common\models;

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

}