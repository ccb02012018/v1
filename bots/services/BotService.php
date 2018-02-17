<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 2/5/18
 * Time: 3:58 PM
 */

namespace services;

use bots\request\Request;

require_once(__DIR__ . '/../request/Request.php');

class BotService extends Request
{
    public static function waitingExchangeBot () {
        return self::request('bot/waiting-exchange');
    }

    /**
     * @param $bot_id int
     * @return int
     */
    public static function startBot($bot_id)
    {
        // ['seconds' => $seconds, 'bot_ins_id' => $botInstance->bot_ins_id]
        $result = self::request('bot/start-bot', ['bot_id' => $bot_id], 'POST');

        if ($result === false) {
            return null;
        }

        return $result;
    }

    public static function esValido($bot_id, $bot_ins_id)
    {
        return self::request('bot/es-valido', ['bot_id' => $bot_id, 'bot_ins_id' => $bot_ins_id]);
    }

    public static function stop($bot_id, $bot_ins_id)
    {
        return self::request('bot/stop', ['bot_id' => $bot_id, 'bot_ins_id' => $bot_ins_id], 'POST');
    }

    public static function wait($bot_id, $bot_ins_id, $lastWakeUp)
    {
        $tmpLastWakeUp = self::sleep($bot_id, $bot_ins_id, $lastWakeUp);
        if ($tmpLastWakeUp == null) {
            return null;
        }
        self::wakeUp($bot_id, $bot_ins_id);
        return $tmpLastWakeUp;
    }

    public static function getMilliseconds($bot_id)
    {
        return self::request('bot/milliseconds', ['bot_id' => $bot_id]);
    }

    /**
     * @param $bot_id int
     * @param $bot_ins_id int
     * @param $lastWakeUp int
     * @return int
     */
    private static function sleep($bot_id, $bot_ins_id, $lastWakeUp)
    {
        $seconds = self::request('bot/sleep', ['bot_id' => $bot_id, 'bot_ins_id' => $bot_ins_id], 'POST');
        if ($seconds === false) {
            return null;
        }
        $tmpLastWakeUp = $lastWakeUp + $seconds;
        time_sleep_until($tmpLastWakeUp);
        return $tmpLastWakeUp;
    }

    /**
     * @param $bot_id
     */
    private static function wakeUp($bot_id, $bot_ins_id)
    {
        self::request('bot/wake-up', ['bot_id' => $bot_id, 'bot_ins_id' => $bot_ins_id], 'POST');
    }
}