<?php

namespace common\models\currency;

use Yii;

/**
 * This is the model class for table "Currency".
 *
 * @property int $cur_id
 * @property string $cur_name
 * @property string $cur_code
 *
 * @property Candlestick[] $candlesticks
 * @property CandlestickHistory[] $candlestickHistories
 * @property CurrencyExchange[] $currencyExchanges
 * @property Exchange[] $curExcExcs
 */
class Currency extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Currency';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_name', 'cur_code'], 'required'],
            [['cur_name', 'cur_code'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_id' => Yii::t('app', 'Cur ID'),
            'cur_name' => Yii::t('app', 'Cur Name'),
            'cur_code' => Yii::t('app', 'Cur Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCandlesticks()
    {
        return $this->hasMany(Candlestick::className(), ['can_cur_id' => 'cur_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCandlestickHistories()
    {
        return $this->hasMany(CandlestickHistory::className(), ['can_his_cur_id' => 'cur_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrencyExchanges()
    {
        return $this->hasMany(CurrencyExchange::className(), ['cur_exc_cur_id' => 'cur_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurExcExcs()
    {
        return $this->hasMany(Exchange::className(), ['exc_id' => 'cur_exc_exc_id'])->viaTable('CurrencyExchange', ['cur_exc_cur_id' => 'cur_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\currency\query\CurrencyQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\currency\query\CurrencyQuery(get_called_class());
    }
}
