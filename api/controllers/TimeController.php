<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 2/5/18
 * Time: 5:02 PM
 */

namespace api\controllers;


use common\models\utils\DateUtil;

class TimeController
{
    public function actionLocalTime () {
        return DateUtil::getLocalTime();
    }
    public function actionServerTime () {
        return DateUtil::getLocalTime();
    }
}