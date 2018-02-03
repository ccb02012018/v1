<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/20/18
 * Time: 6:32 PM
 */

namespace bots;

use bots\request\Bot;
use frontend\models\DateUtil;
use bots\log\Logger;

class ExchangeBot extends \Worker
{

    /**
     * @var int
     */
    private $bot_id;
    /**
     * @var int
     */
    protected $exchange_id;

    /**
     *
     */
    public function run()
    {
        try {

            $this->bot_id = Bot::waitingExchangeBot();

            if ($this->bot_id == null) {
                Logger::writeLog('Sin bot exchange');
                return;
            }

            Bot::startBot($this->bot_id);

            $sync = new Sync();

            do {
                while ($this->findSyncBot($sync)) {
                    continue;
                }

                $this->actualizaExchange();

                if (!$this->esBotValido()) {
                    break;
                }

                $this->sleep();
                $this->wakeUp();
            } while (1);
            Logger::writeLog('Finaliza bot ' . $this->bot_id);
        } catch (\Exception $e) {
            Logger::writeException($e);
        } catch (\Throwable $e) {
            Logger::writeException($e);
        }
        $this->stop();
    }

    private function findSyncBot(&$sync)
    {
        $syncBot = new SyncBot($this->exchange_id, $sync);
        if ($syncBot->isValidBot()) {
            $syncBot->start();
            return true;
        }
        return false;
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    private function sleep()
    {
        $seconds = $this->bot->typeCandlestick->typ_can_milliseconds / 1000;

        $this->bot->bot_sleep = true;
        $this->bot->bot_wake_up += $seconds;
        $this->bot->update();

        Logger::writeLog('Bot sync despierta en ' . $seconds . 'segundos.');

        time_sleep_until($this->bot->bot_wake_up);
    }

    private function startBot()
    {
        $this->bot->bot_running = true;
        $this->bot->bot_wake_up = (DateUtil::getLocalTime() / ($this->bot->typeCandlestick->typ_can_milliseconds / 1000)) * ($this->bot->typeCandlestick->typ_can_milliseconds / 1000);
        $this->bot->update();
        Logger::writeLog('Start bot exchange con nombre ' . $this->bot->bot_name);
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    private function wakeUp()
    {
        $this->bot->bot_sleep = false;
        $this->bot->update();

        Logger::writeLog('Bot exchange despertó.');
    }

    private function esBotValido()
    {
        $this->bot = Bot::findOne($this->bot->bot_id);
        if (!$this->bot->bot_active || !$this->bot->bot_running) {
            Logger::writeLog('Finaliza bot ' . $this->bot->bot_name . ' por cambio de estado.');
            $this->stop();
            return false;
        }
        return true;
    }

    private function actualizaExchange()
    {

    }

    public function stop()
    {
        Logger::writeLog('Stop bot  ' . $this->bot->bot_name);
        $this->bot->bot_running = false;
        $this->bot->bot_sleep = false;
        $this->bot->update();
    }

}