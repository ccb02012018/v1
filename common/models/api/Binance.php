<?php

namespace common\models\api;

use common\custom\CArrayHelper;
use common\models\api\impl\Api;
use common\models\currency\CandlestickHistory;
use common\models\Logger;
use Exception;
use yii\helpers\StringHelper;

class Binance extends \common\librerias\jaggedsoft\API implements Api
{

    const WEIGHT_SINCRONIZE = 1;
    const WEIGHT_CANDLE = 1;

    public function getWeightSincronize()
    {
        return static::WEIGHT_SINCRONIZE;
    }

    public function getWeightCandle()
    {
        return static::WEIGHT_CANDLE;
    }

    private function request($url, $params = [], $method = "GET")
    {
        $opt = [
            "http" => [
                "method" => $method,
                "ignore_errors" => true,
                "header" => "User-Agent: Mozilla/4.0 (compatible; PHP Binance API)\r\n"
            ]
        ];
        $context = stream_context_create($opt);
        $query = http_build_query($params, '', '&');
        error_log($this->base . $url . '?' . $query);
        try {
            $data = file_get_contents($this->base . $url . '?' . $query, false, $context);
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
        return json_decode($data, true);
    }

    /**
     * @param integer $bot_ins_id
     * @param string $currency
     *
     * @param string $currencyMarket
     * @param integer $interval
     * @param integer $startTime
     * @param integer $endTime
     *
     * @return \common\models\currency\CandlestickHistory|null
     */
    public function getCandle($bot_ins_id, $currency, $currencyMarket, $interval, $startTime, $endTime)
    {
        $symbol = $currency . $currencyMarket;

        Logger::writeLog($bot_ins_id, 'v1/klines: ' . "symbol=" . $symbol . " interval=" . $interval . ' limit=1 startTime=' . $startTime . ' endTime=' . $endTime);

        $response = $this->request("v1/klines",
            ["symbol" => $symbol, "interval" => $interval, 'limit' => 1, 'startTime' => $startTime, 'endTime' => $endTime]);

        foreach ($response as $tick) {
            list($openTime, $open, $high, $low, $close, $assetVolume, $closeTime, $baseVolume, $trades, $assetBuyVolume, $takerBuyVolume, $ignored) = $tick;
            $candlestickHistory = new CandlestickHistory();
            $candlestickHistory->can_his_open_time = $openTime;
            $candlestickHistory->can_his_open = $open;
            $candlestickHistory->can_his_high = $high;
            $candlestickHistory->can_his_low = $low;
            $candlestickHistory->can_his_close = $close;
            $candlestickHistory->can_his_volume = $assetVolume;
            $candlestickHistory->can_his_close_time = $closeTime;
            $candlestickHistory->can_his_quote_asset_volume = $baseVolume;
            $candlestickHistory->can_his_number_trades = $trades;
            $candlestickHistory->can_his_tb_base_asset_volume = $assetBuyVolume;
            $candlestickHistory->can_his_tb_quote_asset_volume = $takerBuyVolume;
            $candlestickHistory->can_his_ignore = $ignored;

            return $candlestickHistory;
        }

        return null;
    }

    public function getTimeServer()
    {
        $response = $this->request("v1/time", []);
        return StringHelper::byteSubstr($response['serverTime'], 0, 10);
    }
}
