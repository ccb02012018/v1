<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/20/18
 * Time: 6:32 PM
 */

namespace bots;

use frontend\models\account\Account;
use frontend\models\account\Exchange;
use frontend\models\api\ApiFactory;
use frontend\models\api\impl\Api;
use frontend\models\bot\Bot;
use frontend\models\constant\TypeBot;
use frontend\models\constant\TypeCandlestick;
use frontend\models\DateUtil;
use frontend\models\Logger;

class SyncBot extends \Thread
{
    /**
     * @var int
     */
    private $bot_id;
    /**
     * @var int
     */
    private $exchange_id;
    /**
     * @var Sync
     */
    private $sync;

    public function isValidBot() {
        if ($this->bot_id != null) {
            return true;
        }
        return false;
    }

    public function __construct($exchange_id, &$sync){
        $this->exchange_id = $exchange_id;
        $this->sync = $sync;
        $this->bot = Bot::find()->waitingBot(TypeBot::SYNC, $this->exchange->exc_id);
    }

    /**
     * @return array|Account
     */
    public function getAccount () {
        return $this->exchange->getAccount();
    }

    private function startBot () {
        $this->bot->bot_running = true;
        // SIMULA WAKE_UP
        $this->bot->bot_wake_up = (DateUtil::getLocalTime() / ($this->bot->typeCandlestick->typ_can_milliseconds / 1000) ) * ($this->bot->typeCandlestick->typ_can_milliseconds / 1000);
        $this->bot->update();
        Logger::writeLog('Start bot de sincronización con nombre ' . $this->bot->bot_name);
    }

    /**
     *
     */
    public function run()
    {
        try {
            $this->startBot();

            do {
                $this->sincronize();
                $this->sync->run();

                if (!$this->esBotValido()){
                    break;
                }

                $this->sleep();
                $this->wakeUp();
            } while(1);
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
    private function sleep() {
        $seconds = $this->bot->typeCandlestick->typ_can_milliseconds / 1000;

        $this->bot->bot_sleep = true;
        $this->bot->bot_wake_up += $seconds;
        $this->bot->update();

        Logger::writeLog('Bot sync despierta en ' . $seconds . 'segundos.');

        time_sleep_until($this->bot->bot_wake_up);
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    private function wakeUp() {
        $this->bot->bot_sleep = false;
        $this->bot->update();

        Logger::writeLog('Bot exchange despertó.');
    }

    private function esBotValido() {
        $this->bot = Bot::findOne($this->bot->bot_id);
        if (!$this->bot->bot_active || !$this->bot->bot_running) {
            Logger::writeLog('Finaliza bot sync con nombre ' . $this->bot->bot_name . ' por cambio de estado.');
            $this->stop();
            return false;
        }
        return true;
    }

    public function stop()
    {
        Logger::writeLog('Stop bot  ' . $this->bot->bot_name);
        $this->bot->bot_running = false;
        $this->bot->bot_sleep = false;
        $this->bot->update();
    }

    /**
     * @param $weight
     *
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     */
    private function validateLimit($weight)
    {
        if ($this->getAccount()->acc_limit_weight - $weight >= 0) {
            $this->getAccount()->acc_limit_weight -= $weight;
            $this->getAccount()->update();
        } else {
            Logger::writeLog('Se supera el límite de cuenta ' . $this->getAccount()->acc_name);
            throw new \Exception('Limit weight superado.');
        }
        return true;
    }

    /**
     * @param Api $api
     * @param TypeCandlestick $typeCandlestick
     * @param integer $timePlus
     *
     * @throws \Exception
     * @internal param Account $account
     * @throws \Throwable
     */
    private function sincronize()
    {
        try {
            $api = ApiFactory::getNewApi($this->exchange, $this->exchange->getAccount());

            $this->exchange = Exchange::findOne($this->exchange->exc_id);
            $this->bot = Bot::findOne($this->bot->bot_id);

            $this->validateLimit($api->getWeightSincronize());

            $serverTime = $api->getTimeServer();
            $localTime = DateUtil::getLocalTime();

            $this->exchange->exc_local_time_sincronize = $localTime;
            $this->exchange->exc_server_time_sincronize = $serverTime;
            $this->exchange->exc_difference = $serverTime - $localTime;
            $this->exchange->exc_last_update = $localTime;
            $this->exchange->exc_last_sincronitation = $localTime;
            $this->exchange->update();

        } catch (\Exception $e) {
            throw new \Exception('sincronize: ' . $e->getFile() . ' (' . $e->getLine() . ') ' . $e->getMessage());
        }
    }
}