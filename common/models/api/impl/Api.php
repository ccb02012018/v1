<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/20/18
 * Time: 2:21 PM
 */

namespace common\models\api\impl;

interface Api {
	/**
	 * @param \common\models\currency\Currency $currency
	 *
	 * @param string                        $currencyMarket
	 * @param integer                       $interval
	 * @param integer                       $startTime
	 * @param integer                       $endTime
	 *
	 * @return \common\models\currency\CandlestickHistory
	 */
	public function getCandle ($currency, $currencyMarket, $interval, $startTime, $endTime);

	/**
	 * @return integer
	 */
	public function getTimeServer();

    public function getWeightSincronize();

    public function getWeightCandle();

}