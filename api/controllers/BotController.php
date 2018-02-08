<?php

namespace api\controllers;

use common\models\ApiResult;
use common\models\bot\Bot;
use common\models\log\Log;
use common\models\Logger;
use common\models\utils\DateUtil;
use yii\rest\Controller;

/**
 * Site controller
 */
class BotController extends Controller
{
    public function actionWaitingExchange()
    {
        $bot = Bot::find()->waitingExchangeBot();

        if ($bot != null) {
            Logger::writeLog('bot encontrado ' . $bot->bot_name);
            return (new ApiResult($bot->bot_id))->getResponse();
        }
        return (new ApiResult(-1))->getResponse();
    }

    public function actionStartBot()
    {
        try {
            $bot_id = \Yii::$app->request->post('bot_id');
            $bot = Bot::findOne($bot_id);
            $seconds = (int)($bot->typeCandlestick->typ_can_milliseconds / 1000);
            $bot->bot_running = true;
            $bot->bot_wake_up = (DateUtil::getLocalTime() / $seconds) * ($seconds);
            $bot->update();
            Logger::writeLog('Start bot con nombre ' . $bot->bot_name);
        } catch (\Exception $e) {
            return (new ApiResult($e->getMessage()))->getResponse();
        }
        return (new ApiResult($seconds))->getResponse();
    }

    public function actionEsValido()
    {
        $bot_id = \Yii::$app->request->post('bot_id');
        $bot = Bot::findOne($bot_id);
        if (!$bot->bot_active || !$bot->bot_running) {
            Logger::writeLog('Finaliza bot ' . $bot->bot_name . ' por cambio de estado.');
            $this->stop($bot);
            return (new ApiResult(false))->getResponse();
        }
        return (new ApiResult(true))->getResponse();
    }

    public function actionStop()
    {
        $bot_id = \Yii::$app->request->post('bot_id');
        $bot = Bot::findOne($bot_id);
        $this->stop($bot);
        return (new ApiResult(true))->getResponse();
    }

    /**
     * @param $bot Bot
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    private function stop($bot)
    {
        Logger::writeLog('Stop bot  ' . $bot->bot_name);
        $bot->bot_running = false;
        $bot->bot_sleep = false;
        $bot->update();
        return true;
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    private function actionSleep()
    {
        $bot_id = \Yii::$app->request->post('bot_id');
        $bot = Bot::findOne($bot_id);
        $seconds = $bot->typeCandlestick->typ_can_milliseconds / 1000;

        $bot->bot_sleep = true;
        $bot->bot_wake_up += $seconds;
        $bot->update();

        Logger::writeLog('Bot ' . $bot->bot_name . ' despierta en ' . $seconds . 'segundos.');
        return (new ApiResult($seconds))->getResponse();
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    private function actionWakeUp()
    {
        $bot_id = \Yii::$app->request->post('bot_id');
        $bot = Bot::findOne($bot_id);
        $bot->bot_sleep = false;
        $bot->update();
        Logger::writeLog('Bot ' . $bot->bot_name . ' despertó.');
        return (new ApiResult(true))->getResponse();
    }

}
