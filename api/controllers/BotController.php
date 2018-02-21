<?php

namespace api\controllers;

use Brick\Math\BigDecimal;
use common\custom\CArrayHelper;
use common\models\account\Account;
use common\models\account\Exchange;
use common\models\api\ApiFactory;
use common\models\ApiResult;
use common\models\bot\Bot;
use common\models\bot\BotInstance;
use common\models\constant\TypeCandlestick;
use common\models\currency\Candlestick;
use common\models\currency\CandlestickHistory;
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
            return (new ApiResult(['bot_id' => $bot->bot_id, 'exc_id' => $bot->bot_exc_id]))->getResponse();
        }
        return (new ApiResult(null))->getResponse();
    }

    public function actionWaitingSync()
    {
        $exc_id = \Yii::$app->request->get('exc_id');
        $bot = Bot::find()->waitingSyncBot($exc_id);

        if ($bot != null) {
            return (new ApiResult($bot->bot_id))->getResponse();
        }
        return (new ApiResult(null))->getResponse();
    }

    public function actionWaitingCandle()
    {
        $exc_id = \Yii::$app->request->get('exc_id');
        $bot = Bot::find()->waitingCandleBot($exc_id);

        if ($bot != null) {
            return (new ApiResult($bot->bot_id))->getResponse();
        }
        return (new ApiResult(null))->getResponse();
    }

    public function actionWaitingCandlesGen()
    {
        $exc_id = \Yii::$app->request->get('exc_id');
        $bot = Bot::find()->waitingCandlesGenBot($exc_id);

        if ($bot != null) {
            return (new ApiResult($bot->bot_id))->getResponse();
        }
        return (new ApiResult(null))->getResponse();
    }

    public function actionWaitingVariation()
    {
        $exc_id = \Yii::$app->request->get('exc_id');
        $bot = Bot::find()->waitingVariationBot($exc_id);

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

            $botInstance = new BotInstance($bot->bot_id);
            $botInstance->bot_ins_start = $localTime;

            if (!$botInstance->validate()) {
                Logger::writeFileLog('No es posible guardar instancia de bot: ' . CArrayHelper::toString($botInstance->getErrors()));
                return (new ApiResult(null, 46, 'No es posible guardar instancia de bot: ' . CArrayHelper::toString($botInstance->getErrors())))->getResponse();
            }

            $botInstance->save();
            $bot->bot_bot_ins_id = $botInstance->bot_ins_id;

            if (!$bot->validate()) {
                Logger::writeFileLog('No es posible actualizar bot: ' . CArrayHelper::toString($bot->getErrors()));
                return (new ApiResult(null, 38, 'No es posible actualizar bot: ' . CArrayHelper::toString($bot->getErrors())))->getResponse();
            }

            $bot->update();

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
        $bot_ins_id = \Yii::$app->request->post('bot_ins_id');
        $bot = Bot::findOne($bot_id);
        if (!$bot->bot_active || !$bot->bot_running) {
            Logger::writeLog($bot_ins_id, 'Finaliza bot ' . $bot->bot_name . ' por cambio de estado.');
            return (new ApiResult(false, 85, 'Finaliza bot ' . $bot->bot_name . ' por cambio de estado.'))->getResponse();
        }
        if ($bot->bot_bot_ins_id != $bot_ins_id) {
            Logger::writeLog($bot_ins_id, 'Bot deprecado.');
            return (new ApiResult(null, 85, 'Finaliza bot ' . $bot->bot_name . ' por cambio de estado.'))->getResponse();
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
            $bot->bot_bot_ins_id = null;

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

            Logger::writeLog($bot_ins_id, 'Bot ' . $bot->bot_name . ' despierta en ' . $seconds . ' segundos.');
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

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionSincronize()
    {
        try {
            $exc_id = \Yii::$app->request->post('exc_id');
            $bot_ins_id = \Yii::$app->request->post('bot_ins_id');
            $exchange = Exchange::findOne($exc_id);

            $account = $this->getAccount($exc_id);

            if ($account == null) {
                Logger::writeLog($bot_ins_id, 'Sin cuentas a disposición.');
                return (new ApiResult(null, 193, 'Sin cuentas a disposición.'))->getResponse();
            }

            $api = ApiFactory::getNewApi($exchange, $account);

            $this->validateLimit($api->getWeightSincronize(), $account, $bot_ins_id);

            $serverTime = $api->getTimeServer();
            $localTime = DateUtil::getLocalTime();

            $exchange->exc_local_time_sincronize = $localTime;
            $exchange->exc_server_time_sincronize = $serverTime;
            $exchange->exc_difference = $serverTime - $localTime;
            $exchange->exc_last_update = $localTime;
            $exchange->exc_last_sincronitation = $localTime;
            $exchange->update();

        } catch (\Exception $e) {
            Logger::writeExceptionFileLog($e);
            return (new ApiResult(null, $e->getLine(), $e->getMessage()))->getResponse();
        }

        return (new ApiResult(true))->getResponse();
    }

    private function getAccount($exc_id)
    {
        return Account::find()->byExchange($exc_id);
    }

    /**
     * @param $weight
     *
     * @param $account Account
     * @param $bot_ins_id int
     * @return bool
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    private function validateLimit($weight, $account, $bot_ins_id)
    {
        if ($account->acc_limit_weight - $weight >= 0) {
            $account->acc_limit_weight -= $weight;
            $account->update();
        } else {
            Logger::writeLog($bot_ins_id, 'Se supera el límite de cuenta ' . $account->acc_name);
            throw new \Exception('Limit weight superado.');
        }
        return true;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionLastWakeUp()
    {
        $plus = 2;
        try {
            $bot_id = \Yii::$app->request->post('bot_id');
            $bot = Bot::findOne($bot_id);
            $exchange = $bot->exchange;

            $seconds = $bot->typeCandlestick->typ_can_milliseconds / 1000;

            $localTime = DateUtil::getLocalTime();

            $serverTime = $localTime + $exchange->exc_difference;

            $fraccion = (int)($serverTime / $seconds);
            $lastWakeUp = $fraccion * $seconds;

            $bot->bot_wake_up = $lastWakeUp;

            if (!$bot->validate()) {
                Logger::writeFileLog('No es posible actualizar bot: ' . CArrayHelper::toString($bot->getErrors()));
                return (new ApiResult(null, 275, 'No es posible actualizar bot: ' . CArrayHelper::toString($bot->getErrors())))->getResponse();
            }

            $bot->update();

            $bot_ins_id = \Yii::$app->request->post('bot_ins_id');
            //$botInstance = BotInstance::findOne($bot_ins_id);

            Logger::writeLog($bot_ins_id, 'Bot set $lastWakeUp=' . $lastWakeUp);
            return (new ApiResult($lastWakeUp - $exchange->exc_difference + $bot->bot_delay))->getResponse();
        } catch (\Exception $e) {
            Logger::writeExceptionFileLog($e);
            return (new ApiResult(null, $e->getLine(), $e->getMessage()))->getResponse();
        }
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionGetCandles()
    {
        try {
            $bot_ins_id = \Yii::$app->request->post('bot_ins_id');
            $botInsance = BotInstance::findOne($bot_ins_id);
            $bot = $botInsance->bot;
            $exchange = $bot->exchange;
            $cryptos = $exchange->currencies;

            foreach ($cryptos as $currency) {
                if ($currency->cur_id == $bot->currency->cur_id) {
                    continue;
                }

                $account = $this->getAccount($exchange->exc_id);

                if ($account == null) {
                    Logger::writeLog($bot_ins_id, 'Sin cuentas a disposición.');
                    return (new ApiResult(null, 193, 'Sin cuentas a disposición.'))->getResponse();
                }
                $api = ApiFactory::getNewApi($exchange, $account);

                $this->validateLimit($api->getWeightCandle(), $account, $bot_ins_id);

                $startTime = ($bot->bot_wake_up - $bot->typeCandlestick->typ_can_milliseconds / 1000) . '000';
                $endTime = ($bot->bot_wake_up - 1) . '999';

                $candlestickHistory = $api->getCandle($bot_ins_id, $currency->cur_code, $bot->currency->cur_code, $bot->typeCandlestick->typ_can_description, $startTime, $endTime);

                $candlestickHistory->can_his_cur_id = $currency->cur_id;
                $candlestickHistory->can_his_exc_id = $exchange->exc_id;
                $candlestickHistory->can_his_typ_can_id = $bot->typeCandlestick->typ_can_id;

                if ($candlestickHistory->validate()) {
                    $candlestickHistory->save();
                } else {
                    Logger::writeLog($bot_ins_id, 'No es posible ingresar candle:  ' . json_encode($candlestickHistory->getErrors()));
                    return (new ApiResult(null, 333, 'No es posible ingresar candle:  ' . json_encode($candlestickHistory->getErrors())))->getResponse();
                }
            }

            Logger::writeLog($bot_ins_id, 'Velas cargadas.');
            return (new ApiResult(true))->getResponse();
        } catch (\Exception $e) {
            Logger::writeExceptionFileLog($e);
            return (new ApiResult(false, $e->getLine(), $e->getLine() . ' ' . $e->getFile() . ' ' . $e->getMessage()))->getResponse();
        }
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionGenerateCandles()
    {
        try {
            $bot_ins_id = \Yii::$app->request->post('bot_ins_id');
            $botInsance = BotInstance::findOne($bot_ins_id);
            $bot = $botInsance->bot;
            $exchange = $bot->exchange;
            $cryptos = $exchange->currencies;

            $typeCandlestick = $bot->typeCandlestick;
            $candleTypes = TypeCandlestick::find()->biggerThan($typeCandlestick->typ_can_milliseconds);

            foreach ($cryptos as $currency) {
                if ($currency->cur_id == $bot->currency->cur_id) {
                    continue;
                }

                Logger::writeLog($bot_ins_id, 'Se evaluará generar ' . count($candleTypes) . ' tipo(s) de vela(s)');

                //$seconds = $typeCandlestick->typ_can_milliseconds / 1000;
                $previous = $bot->bot_wake_up - 1;
                $end = $previous . '999';

                foreach ($candleTypes as $candleType) {

                    // VALIDAR VELA SI APLICA O NO

                    $seconds = $candleType->typ_can_milliseconds / 1000;
                    $previous = $bot->bot_wake_up - $seconds;
                    $start = $previous . '000';

                    $wakeUp = ($bot->bot_wake_up / $seconds) * $seconds;

                    if ($bot->bot_wake_up != $wakeUp) {
                        Logger::writeLog($bot_ins_id, 'start=' . $start . ' end=' . $end . ', No aplica generar vela de ' . $candleType->typ_can_description);
                        continue;
                    }

                    $iCandles = $candleType->typ_can_milliseconds / $typeCandlestick->typ_can_milliseconds;

                    $candles = CandlestickHistory::find()->byTime($exchange->exc_id, $currency->cur_id, $typeCandlestick->typ_can_id, $start, $end);

                    if (count($candles) != $iCandles) {
                        Logger::writeLog($bot_ins_id, 'start=' . $start . ' end=' . $end . ', No es posible generar vela de ' . $candleType->typ_can_description . ', se encuentran ' . count($candles) . ' resultados y deberían ser ' . $iCandles);
                        continue;
                    }

                    $newCandle = new Candlestick();
                    $first = true;
                    foreach ($candles as $candle) {
                        if ($first || $newCandle->can_open_time > $candle->can_his_open_time) {
                            $newCandle->can_open_time = $candle->can_his_open_time;
                            $newCandle->can_open = $candle->can_his_open;
                        }
                        if ($first || $newCandle->can_close_time < $candle->can_his_close_time) {
                            $newCandle->can_close_time = $candle->can_his_close_time;
                            $newCandle->can_close = $candle->can_his_close;
                        }
                        if ($first || $newCandle->can_high < $candle->can_his_high) {
                            $newCandle->can_high = $candle->can_his_high;
                        }
                        if ($first || $newCandle->can_low > $candle->can_his_low) {
                            $newCandle->can_low = $candle->can_his_low;
                        }
                        if ($first) {
                            $newCandle->can_volume = $candle->can_his_volume;
                            $newCandle->can_quote_asset_volume = $candle->can_his_quote_asset_volume;
                            $newCandle->can_number_trades = $candle->can_his_number_trades;
                            $newCandle->can_tb_base_asset_volume = $candle->can_his_tb_base_asset_volume;
                            $newCandle->can_tb_quote_asset_volume = $candle->can_his_tb_quote_asset_volume;

                            $first = false;
                            continue;
                        }

                        $newCandle->can_volume += $candle->can_his_volume;
                        $newCandle->can_quote_asset_volume += $candle->can_his_quote_asset_volume;
                        $newCandle->can_number_trades += $candle->can_his_number_trades;
                        $newCandle->can_tb_base_asset_volume += $candle->can_his_tb_base_asset_volume;
                        $newCandle->can_tb_quote_asset_volume += $candle->can_his_tb_quote_asset_volume;
                    }

                    $newCandle->can_cur_id = $currency->cur_id;
                    $newCandle->can_exc_id = $exchange->exc_id;
                    $newCandle->can_typ_can_id = $candleType->typ_can_id;

                    if (!$newCandle->validate()) {
                        Logger::writeLog($bot_ins_id, 'No es posible generar vela ' . $candleType->typ_can_description . ' Error: ' . CArrayHelper::toString($newCandle->getErrors()));
                        continue;
                    }

                    $newCandle->save();
                    Logger::writeLog($bot_ins_id, 'Genera vela de ' . $candleType->typ_can_description);
                }
            }

            Logger::writeLog($bot_ins_id, 'Velas generadas.');
            return (new ApiResult(true))->getResponse();
        } catch (\Exception $e) {
            Logger::writeExceptionFileLog($e);
            return (new ApiResult(false, $e->getLine(), $e->getLine() . ' ' . $e->getFile() . ' ' . $e->getMessage()))->getResponse();
        }
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function actionCalculateVariation()
    {
        try {
            $bot_ins_id = \Yii::$app->request->post('bot_ins_id');
            $botInsance = BotInstance::findOne($bot_ins_id);
            $bot = $botInsance->bot;
            $exchange = $bot->exchange;
            $cryptos = $exchange->currencies;

            $typeCandlestick = $bot->typeCandlestick;
            $candleTypes = TypeCandlestick::find()->biggerThan($typeCandlestick->typ_can_milliseconds);

            foreach ($cryptos as $currency) {
                if ($currency->cur_id == $bot->currency->cur_id) {
                    continue;
                }

                Logger::writeLog($bot_ins_id, 'Se evaluará generar ' . count($candleTypes) . ' tipo(s) de vela(s)');

                //$seconds = $typeCandlestick->typ_can_milliseconds / 1000;
                $previous = $bot->bot_wake_up - 1;
                $end = $previous * 1000 + 999;

                foreach ($candleTypes as $candleType) {

                    // VALIDAR VELA SI APLICA O NO

                    $seconds = $candleType->typ_can_milliseconds / 1000;

                    $previous = $bot->bot_wake_up - $seconds;
                    $start = $previous * 1000;

                    $wakeUp = ($bot->bot_wake_up / $seconds) * $seconds;
                    //diferencia entres las fechas que se comparan OJO ACA!!!
                    if ($bot->bot_wake_up != $wakeUp) {
                        Logger::writeLog($bot_ins_id, 'start=' . $start . ' end=' . $end . ', No aplica calcular vela de ' . $candleType->typ_can_description);
                        continue;
                    }

                    $candle = Candlestick::find()->byTime($exchange->exc_id, $currency->cur_id, $candleType->typ_can_id, $start, $end);
                    if ($candle == null) {
                        Logger::writeLog($bot_ins_id, 'No existe vela ' . $candleType->typ_can_description . ' de ' . $currency->cur_name . ', start=' . $start . ', end=' . $end);
                        continue;
                    }

                    $start -= $candleType->typ_can_milliseconds;
                    $end -= $candleType->typ_can_milliseconds;

                    $prevCandle = Candlestick::find()->byTime($exchange->exc_id, $currency->cur_id, $candleType->typ_can_id, $start, $end);
                    if ($prevCandle == null) {
                        Logger::writeLog($bot_ins_id, 'No existe vela anterior  ' . $candleType->typ_can_description . ' de ' . $currency->cur_name . ', start=' . $start . ', end=' . $end);
                        continue;
                    }

                    $previous_volume = BigDecimal::of($prevCandle->can_volume);
                    $current_volume = BigDecimal::of($candle->can_volume);

                    $candle->can_previous_volume = $previous_volume;
                    $candle->can_variation_volume = $previous_volume->minus($current_volume)->multipliedBy(100)->dividedBy($previous_volume);

                    $previous_price = BigDecimal::of($prevCandle->can_close);
                    $current_price = BigDecimal::of($candle->can_close);

                    $candle->can_previous_price = $previous_price;
                    $candle->can_variation_price = $previous_price->minus($current_price)->multipliedBy(100)->dividedBy($previous_price);

                    error_log($candle->can_previous_price);
                    error_log($candle->can_variation_price);

                    if (!$candle->validate()) {
                        Logger::writeLog($bot_ins_id, 'No es posible generar vela ' . $candleType->typ_can_description . ' Error: ' . CArrayHelper::toString($candle->getErrors()));
                        continue;
                    }

                    $candle->save();
                    Logger::writeLog($bot_ins_id, 'Genera vela de ' . $candleType->typ_can_description);
                }
            }

            Logger::writeLog($bot_ins_id, 'Velas generadas.');
            return (new ApiResult(true))->getResponse();
        } catch (\Exception $e) {
            Logger::writeExceptionFileLog($e);
            return (new ApiResult(false, $e->getLine(), $e->getLine() . ' ' . $e->getFile() . ' ' . $e->getMessage()))->getResponse();
        }
    }

}
