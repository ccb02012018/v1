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
        $seconds = self::request('bot/start-bot', ['bot_id' => $bot_id], 'POST');

        if ($seconds === false) {
            return -1;
        }
        return $seconds;
    }

    public static function esValido($bot_id)
    {
        self::request('bot/es-valido', ['bot_id' => $bot_id]);
    }

    public static function stop($bot_id)
    {
        self::request('bot/stop', ['bot_id' => $bot_id]);
    }

    public static function wait($bot_id, $lastWakeUp)
    {
        $tmpLastWakeUp = self::sleep($bot_id, $lastWakeUp);
        self::wakeUp($bot_id);
        return $tmpLastWakeUp;
    }

    public static function getMilliseconds($bot_id)
    {
        return self::request('bot/milliseconds', ['bot_id' => $bot_id]);
    }

    /**
     * @param $bot_id int
     * @param $lastWakeUp int
     * @return int
     */
    private function sleep($bot_id, $lastWakeUp)
    {
        $seconds = self::request('bot/sleep', ['bot_id' => $bot_id]);
        $tmpLastWakeUp = $lastWakeUp + $seconds;
        time_sleep_until($tmpLastWakeUp);
        return $tmpLastWakeUp;
    }

    /**
     * @param $bot_id
     */
    private function wakeUp($bot_id)
    {
        self::request('bot/wake-up', ['bot_id' => $bot_id]);
    }
}