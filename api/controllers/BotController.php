<?php

namespace api\controllers;

use common\custom\CArrayHelper;
use common\models\ApiResult;
use common\models\bot\Bot;
use common\models\bot\BotInstance;
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
            return (new ApiResult($bot->bot_id))->getResponse();
        }
        return (new ApiResult(null))->getResponse();
    }

    public function actionStartBot()
    {
        try {
            $bot_id = \Yii::$app->request->post('bot_id');
            $bot = Bot::findOne($bot_id);

            $localTime = DateUtil::getLocalTime();
            $seconds = (int)($bot->typeCandlestick->typ_can_milliseconds / 1000);
            $bot->bot_running = true;
            $bot->bot_wake_up = ($localTime / $seconds) * ($seconds);

            if (!$bot->validate()) {
                Logger::writeFileLog('No es posible actualizar bot: ' . CArrayHelper::toString($bot->getErrors()));
                return (new ApiResult(null, 38, 'No es posible actualizar bot: ' . CArrayHelper::toString($bot->getErrors())))->getResponse();
            }

            $botInstance = new BotInstance($bot->bot_id);
            $botInstance->bot_ins_start = $localTime;

            if (!$botInstance->validate()) {
                Logger::writeFileLog('No es posible guardar instancia de bot: ' . CArrayHelper::toString($botInstance->getErrors()));
                return (new ApiResult(null, 46, 'No es posible guardar instancia de bot: ' . CArrayHelper::toString($botInstance->getErrors())))->getResponse();
            }

            $bot->update();
            $botInstance->save();

            Logger::writeLog($botInstance->bot_ins_id, 'Start bot con nombre ' . $bot->bot_name);
        } catch (\Exception $e) {
            Logger::writeExceptionFileLog($e);
            return (new ApiResult(null, $e->getLine(), $e->getMessage()))->getResponse();
        }
        return (new ApiResult(['seconds' => $seconds, 'bot_ins_id' => $botInstance->bot_ins_id]))->getResponse();
    }

    public function actionEsValido()
    {
        $bot_id = \Yii::$app->request->post('bot_id');
        $bot = Bot::findOne($bot_id);
        if (!$bot->bot_active || !$bot->bot_running) {
            $bot_ins_id = \Yii::$app->request->post('bot_ins_id');
            //$botInstance = BotInstance::findOne($bot_ins_id);

            Logger::writeLog($bot_ins_id, 'Finaliza bot ' . $bot->bot_name . ' por cambio de estado.');
            //$this->stop($bot, $botInstance);
            return (new ApiResult(false))->getResponse();
        }
        return (new ApiResult(true))->getResponse();
    }

    public function actionStop()
    {
        try {
            $bot_id = \Yii::$app->request->post('bot_id');
            $bot = Bot::findOne($bot_id);

            $bot_ins_id = \Yii::$app->request->post('bot_ins_id');
            $botInstance = BotInstance::findOne($bot_ins_id);

            $bot->bot_running = false;
            $bot->bot_sleep = false;

            if (!$bot->validate()) {
                Logger::writeFileLog('No es posible actualizar bot: ' . CArrayHelper::toString($bot->getErrors()));
                return (new ApiResult(null, 91, 'No es posible actualizar bot: ' . CArrayHelper::toString($bot->getErrors())))->getResponse();
            }

            if ($botInstance != null) {
                $botInstance->bot_ins_end = DateUtil::getLocalTime();

                if (!$botInstance->validate()) {
                    Logger::writeFileLog('No es posible guardar instancia de bot: ' . CArrayHelper::toString($botInstance->getErrors()));
                    return (new ApiResult(null, 99, 'No es posible guardar instancia de bot: ' . CArrayHelper::toString($botInstance->getErrors())))->getResponse();
                }

                $botInstance->update();
            }

            $bot->update();

            Logger::writeLog($bot_ins_id, 'Stop bot  ' . $bot->bot_name);
            return (new ApiResult(true))->getResponse();
        } catch (\Exception $e) {
            Logger::writeExceptionFileLog($e);
            return (new ApiResult(null, $e->getLine(), $e->getMessage()))->getResponse();
        }
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionSleep()
    {
        try {
            $bot_id = \Yii::$app->request->post('bot_id');
            $bot = Bot::findOne($bot_id);

            $seconds = $bot->typeCandlestick->typ_can_milliseconds / 1000;

            $bot->bot_sleep = true;
            $bot->bot_wake_up += $seconds;

            if (!$bot->validate()) {
                Logger::writeFileLog('No es posible actualizar bot: ' . CArrayHelper::toString($bot->getErrors()));
                return (new ApiResult(null, 131, 'No es posible actualizar bot: ' . CArrayHelper::toString($bot->getErrors())))->getResponse();
            }

            $bot->update();

            $bot_ins_id = \Yii::$app->request->post('bot_ins_id');
            //$botInstance = BotInstance::findOne($bot_ins_id);

            Logger::writeLog($bot_ins_id, 'Bot ' . $bot->bot_name . ' despierta en ' . $seconds . 'segundos.');
            return (new ApiResult($seconds))->getResponse();
        } catch (\Exception $e) {
            Logger::writeExceptionFileLog($e);
            return (new ApiResult(null, $e->getLine(), $e->getMessage()))->getResponse();
        }
    }

    /**
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionWakeUp()
    {
        $bot_id = \Yii::$app->request->post('bot_id');
        $bot = Bot::findOne($bot_id);
        $bot->bot_sleep = false;
        $bot->update();
        $bot_ins_id = \Yii::$app->request->post('bot_ins_id');
        Logger::writeLog($bot_ins_id, 'Bot ' . $bot->bot_name . ' despertó.');
        return (new ApiResult(true))->getResponse();
    }

}
