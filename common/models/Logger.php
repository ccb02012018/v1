<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/20/18
 * Time: 9:36 PM
 */

namespace common\models;

use common\custom\CArrayHelper;
use common\models\log\Log;

class Logger
{
    public static function writeException($bot_ins_id, \Exception $e)
    {
        try {
            $log = new Log($bot_ins_id,$e->getMessage());
            if (!$log->validate()) {
                self::writeFileLog($e->getMessage() . ': ' . CArrayHelper::toString($log->getErrors()));
            } else {
                $log->save();
            }
        } catch (\Exception $exception) {
            self::writeFileLog($e->getMessage() . ': ' . $exception);
        }
    }

    public static function writeLog($bot_ins_id, $message)
    {
        try {
            $log = new Log($bot_ins_id, $message);
            if (!$log->validate()) {
                self::writeFileLog($message . ': ' . CArrayHelper::toString($log->getErrors()));
            } else {
                $log->save();
            }
        } catch (\Exception $exception) {
            self::writeFileLog($message . ': ' . $exception);
        }
    }

    public static function writeFileLog($message)
    {
        error_log($message, 0);
    }

    public static function writeExceptionFileLog($exception)
    {
        error_log('Error: ' . $exception->getMessage(), 0);
    }
}