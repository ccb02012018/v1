<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/20/18
 * Time: 2:16 PM
 */

namespace common\models\api;
use common\models\account\Exchange;


/**
 * Class ApiFactory
 *
 * @package common\models
 */
class ApiFactory {
	/**
	 * @param Exchange  $exchange
	 * @param \common\models\account\Account $account
	 *
	 * @return impl\Api
	 */
	static function getNewApi($exchange, $account) {
		switch ($exchange->exc_id) {
			case Exchange::BINANCE:
				return new Binance($account->acc_key, $account->acc_secret);
		}
		return null;
	}
}