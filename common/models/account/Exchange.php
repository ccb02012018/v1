<?php

namespace common\models\account;

use common\models\account\query\ExchangeQuery;
use common\models\currency\Candlestick;
use common\models\currency\CandlestickHistory;
use common\models\currency\Currency;
use common\models\currency\CurrencyExchange;
use Yii;

/**
 * This is the model class for table "Exchange".
 *
 * @property int $exc_id
 * @property string $exc_name
 * @property int $exc_limit
 * @property boolean $exc_taked
 * @property int $exc_take_time
 * @property int $exc_last_update
 * @property int $exc_local_time_sincronize
 * @property int $exc_server_time_sincronize
 * @property int $exc_difference
 * @property int $exc_last_reset_limit
 * @property int $exc_last_sincronitation
 *
 * @property Account[] $accounts
 * @property Candlestick[] $candlesticks
 * @property CandlestickHistory[] $candlestickHistories
 * @property CurrencyExchange[] $currencyExchanges
 * @property Currency[] $currencies
 */
class Exchange extends \yii\db\ActiveRecord
{
    const BINANCE = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Exchange';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['exc_name'], 'required'],
            [['exc_taked'], 'boolean'],
            [['exc_limit', 'exc_take_time', 'exc_local_time_sincronize', 'exc_server_time_sincronize', 'exc_difference', 'exc_last_reset_limit', 'exc_last_update', 'exc_last_sincronitation'], 'integer'],
            [['exc_name'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'exc_id' => Yii::t('common', 'Exc ID'),
            'exc_name' => Yii::t('common', 'Exc Name'),
            'exc_limit' => Yii::t('common', 'Exc Limit'),
            'exc_taked' => Yii::t('common', 'Exc Taked'),
            'exc_take_time' => Yii::t('common', 'Exc Take Time'),
            'exc_local_time_sincronize' => Yii::t('common', 'Exc Local Time Sincronize'),
            'exc_server_time_sincronize' => Yii::t('common', 'Exc Server Time Sincronize'),
            'exc_difference' => Yii::t('common', 'Exc Difference'),
            'exc_last_reset_limit' => Yii::t('common', 'Exc Last Reset Limit'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccounts()
    {
        return $this->hasMany(Account::className(), ['acc_exc_id' => 'exc_id']);
    }

    /**
     * @return Account|array
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['acc_exc_id' => 'exc_id'])->orderBy('acc_limit_weight DESC')->limit(1)->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCandlesticks()
    {
        return $this->hasMany(Candlestick::className(), ['can_exc_id' => 'exc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCandlestickHistories()
    {
        return $this->hasMany(CandlestickHistory::className(), ['can_his_exc_id' => 'exc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrencyExchanges()
    {
        return $this->hasMany(CurrencyExchange::className(), ['cur_exc_exc_id' => 'exc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrencies()
    {
        return $this->hasMany(Currency::className(), ['cur_id' => 'cur_exc_cur_id'])->viaTable('CurrencyExchange', ['cur_exc_exc_id' => 'exc_id']);
    }

    /**
     * @inheritdoc
     * @return ExchangeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ExchangeQuery(get_called_class());
    }
}