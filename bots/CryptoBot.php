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
use frontend\models\currency\Currency;
use Yii;

class CryptoBot {

	const RETURN_ERROR = 1;
	const RETURN_SUCCESS = 2;


	/**
	 * @var Bot
	 */
	private $bot;
	/**
	 * @var Account
	 */
	private $account;
	/**
	 * @var Exchange
	 */
	private $exchange;

	/**
	 *
	 */
	public function run () {
		try {
			$this->bot = Bot::find()->waitingBot(TypeBot::VOLUME);

			if ($this->bot == null) {
				return static::RETURN_SUCCESS;
			}

			$this->bot->bot_active = true;
			$this->bot->update();

			Logger::writeLog('Start bot volumen con nombre ' . $this->bot->bot_name);

			$this->account = $this->bot->account;
			$this->exchange = $this->account->exchange;
			$typeCandlestick = $this->bot->typeCandlestick;

			$api = ApiFactory::getNewApi($this->exchange, $this->account);

			$nextRequestTime = $this->sincronize($api, $typeCandlestick, 10);

			$cryptos = $this->exchange->currencies;

			$this->loadCandlesticks($typeCandlestick, $cryptos, $api, $nextRequestTime);

		} catch (\Exception $e) {
			Logger::writeException($e);

			return static::RETURN_ERROR;
		}

		return static::RETURN_SUCCESS;
	}

	public function stop () {
		Logger::writeLog('Stop bot  ' . $this->bot->bot_name);
		$this->bot->bot_active = false;
		$this->bot->bot_sleep = false;
		$this->bot->update();
	}

	/**
	 * @param $weight
	 *
	 * @return bool
	 * @throws \Exception
	 */
	private function validateLimit ($weight) {
		$this->account = Account::findOne($this->account->acc_id);
		if ($this->account->acc_limit_weight - $weight >= 0) {
			$this->account->acc_limit_weight = $this->account->acc_limit_weight - $weight;
			$this->account->update();
		} else {
			Logger::writeLog('Se supera el límite de cuenta ' . $this->account->acc_name);
			throw new \Exception('Limit weight superado.');
		}
		return true;
	}

	/**
	 * @param Api             $api
	 * @param TypeCandlestick $typeCandlestick
	 * @param integer         $timePlus
	 *
	 * @return float|int
	 * @throws \Exception
	 * @internal param Account $account
	 */
	private function sincronize ($api, $typeCandlestick, $timePlus) {
		try {

			$this->exchange = Exchange::findOne($this->exchange->exc_id);
			$this->bot = Bot::findOne($this->bot->bot_id);

			$this->validateLimit(static::WEIGHT_SINCRONIZE);

			$serverTime = $api->getTimeServer();
			$fecha = date_create();
			$localTime = date_format($fecha, 'U');

			$nextRequestTime = ((int) ($serverTime / ($typeCandlestick->typ_can_milliseconds / 1000)) + 1) * ($typeCandlestick->typ_can_milliseconds / 1000);

			$difference = $nextRequestTime - $serverTime;

			$this->exchange->exc_local_time_sincronize = $localTime;
			$this->exchange->exc_server_time_sincronize = $serverTime;
			$this->exchange->exc_difference = $serverTime - $localTime;
			$this->exchange->update();

			$this->bot->bot_sleep = true;
			$this->bot->bot_wake_up = $localTime + $difference + $timePlus;
			$this->bot->update();

			Logger::writeLog('Sincroniza despierta en ' . $this->bot->bot_wake_up);

			time_sleep_until($this->bot->bot_wake_up);

			$this->bot->bot_sleep = false;
			$this->bot->update();

			return $nextRequestTime;
		} catch (\Exception $e) {
			throw new \Exception('sincronize: ' . $e->getFile() . ' (' . $e->getLine() . ') ' . $e->getMessage());
		}
	}

	/**
	 * @return false|string
	 */
	private function getLocalTime () {
		$fecha = date_create();
		return date_format($fecha, 'U');
	}

	/**
	 * @param $typeCandlestick TypeCandlestick
	 * @param $cryptos         Currency[]
	 * @param $api             Api
	 *
	 * @param $nextRequestTime integer
	 *
	 * @throws \Exception
	 * @internal param Account $account
	 */
	private function loadCandlesticks ($typeCandlestick, $cryptos, $api, $nextRequestTime) {
		try {

			$nextRequest = $nextRequestTime;

			do {
				// ACTUALIZA ESTADO DE BOT, RENUEVA ESTADO Y MONEDA DE CAMBIO
				$this->bot = Bot::findOne($this->bot->bot_id);

				// SI NO ESTÁ ACTIVO SE TERMINA
				if (!$this->bot->bot_active) {
					Logger::writeLog('Finaliza bot ' . $this->bot->bot_name . ' por cambio de estado.');
					$this->stop();
					return;
				}

				$marketCurrency = $this->bot->currency;

//				$connection = Yii::$app->db;
//				$transaction = $connection->beginTransaction();

				try {
					foreach ($cryptos as $currency) {
						if ($currency->cur_id == $marketCurrency->cur_id) {
							continue;
						}

						$this->validateLimit(static::WEIGHT_CANDLE);
						$candlestickHistory = $api->getCandle($currency, $marketCurrency->cur_code, $typeCandlestick->typ_can_description,
							$nextRequest . '000', ($nextRequest + $typeCandlestick->typ_can_milliseconds / 1000 - 1) . '999');

						$candlestickHistory->can_his_cur_id = $currency->cur_id;
						$candlestickHistory->can_his_exc_id = $this->exchange->exc_id;
						$candlestickHistory->can_his_typ_can_id = $typeCandlestick->typ_can_id;

						if ($candlestickHistory->validate()) {
							$candlestickHistory->save();
						} else {
							Logger::writeLog('No es posible ingresar candle:  ' . json_encode($candlestickHistory->getErrors()));
						}
					}
					//$transaction->commit();
				} catch (\Exception $e) {
					//$transaction->rollBack();
					throw $e;
				}

				Logger::writeLog('Actualiza monedas desde ' . $nextRequest . ' hasta ' . ($nextRequest + $typeCandlestick->typ_can_milliseconds / 1000 - 1));

				$wake_up = $this->bot->bot_wake_up + $typeCandlestick->typ_can_milliseconds / 1000;
				$localTime = $this->getLocalTime();

				if ($localTime > $wake_up) {
					throw new \Exception('Bot desincronizado.');
				}

				$this->bot->bot_sleep = true;
				$this->bot->bot_wake_up = $wake_up;
				$this->bot->update();

				Logger::writeLog('Bot volumen despierta en ' . $this->bot->bot_wake_up);

				time_sleep_until($this->bot->bot_wake_up);

				$this->bot->bot_sleep = false;
				$this->bot->update();

				$nextRequest += $typeCandlestick->typ_can_milliseconds / 1000;
			} while (true);

		} catch (\Exception $e) {
			throw new \Exception('loadCandlesticks: ' . $e->getFile() . ' (' . $e->getLine() . ') ' . $e->getMessage());
		}
	}

	public function prueba () {

		$this->bot = Bot::find()->waitingBot(TypeBot::VOLUME);

		if ($this->bot == null) {
			return;
		}

		$this->account = $this->bot->account;
		$this->exchange = $this->account->exchange;

		$api = ApiFactory::getNewApi($this->exchange, $this->account);


		$serverTime = 1516511220;
		$nextRequestTime = ((int) ($serverTime / ($this->bot->typeCandlestick->typ_can_milliseconds / 1000)) + 1) * ($this->bot->typeCandlestick->typ_can_milliseconds / 1000) + 10;

		$parse = (int) ($serverTime / ($this->bot->typeCandlestick->typ_can_milliseconds / 1000));
		$parse = $parse * ($this->bot->typeCandlestick->typ_can_milliseconds / 1000);

		$fecha = date_create();
		date_timestamp_set($fecha, $serverTime);

		echo '$serverTime -> ' . date_format($fecha, 'U = Y-m-d H:i:s') . "</br>";

		date_timestamp_set($fecha, $parse);
		echo '$parse -> ' . date_format($fecha, 'U = Y-m-d H:i:s') . "</br>";

		date_timestamp_set($fecha, $nextRequestTime);
		echo '$nextRequestTime -> ' . date_format($fecha, 'U = Y-m-d H:i:s') . "</br>";


		$difference = $nextRequestTime - $serverTime;

		$fecha = date_create();
		$localTime = date_format($fecha, 'U');


		date_timestamp_set($fecha, $localTime);
		echo '$localTime -> ' . date_format($fecha, 'U = Y-m-d H:i:s') . "</br>";

		date_timestamp_set($fecha, $localTime + $difference);
		echo '$localTime+plus -> ' . date_format($fecha, 'U = Y-m-d H:i:s') . "</br>";
	}

}