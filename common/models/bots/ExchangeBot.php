<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/20/18
 * Time: 6:32 PM
 */

namespace common\models\bots;

use common\models\account\Exchange;
use common\models\bot\Bot;
use common\models\utils\DateUtil;
use common\models\Logger;

class ExchangeBot extends \Worker
{

    /**
     * @var Bot
     */
    private $bot;
    /**
     * @var Exchange
     */
    protected $exchange;

    /* override default inheritance behaviour for the new threaded context */
    public function start($options = PTHREADS_INHERIT_ALL)
    {
        return parent::start(PTHREADS_INHERIT_ALL);
    }

    /**
     *
     */
    public function run()
    {
        try {
            require_once(__DIR__ . '/../../../vendor/autoload.php');
            require_once(__DIR__ . '/../../../backend/web/index.php');

            $this->bot = Bot::find()->waitingExchangeBot();

            if ($this->bot == null) {
                Logger::writeLog('Sin bot exchange');
                return;
            }

            $this->startBot();

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
            Logger::writeLog('Finaliza bot ' . $this->bot->bot_name);
        } catch (\Exception $e) {
            Logger::writeException($e);
        } catch (\Throwable $e) {
            Logger::writeException($e);
        }
        $this->stop();
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

    private function findSyncBot(&$sync)
    {
        $syncBot = new SyncBot($this->exchange, $sync);
        if ($syncBot->isValidBot()) {
            $syncBot->start();
            return true;
        }
        return false;
    }

}