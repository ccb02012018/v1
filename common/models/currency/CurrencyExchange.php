<?php

namespace common\models\currency;

use Yii;

/**
 * This is the model class for table "CurrencyExchange".
 *
 * @property int $cur_exc_cur_id
 * @property int $cur_exc_exc_id
 * @property string $cur_exc_code
 *
 * @property Currency $curExcCur
 * @property Exchange $curExcExc
 */
class CurrencyExchange extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'CurrencyExchange';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cur_exc_cur_id', 'cur_exc_exc_id'], 'required'],
            [['cur_exc_cur_id', 'cur_exc_exc_id'], 'integer'],
            [['cur_exc_code'], 'string', 'max' => 45],
            [['cur_exc_cur_id', 'cur_exc_exc_id'], 'unique', 'targetAttribute' => ['cur_exc_cur_id', 'cur_exc_exc_id']],
            [['cur_exc_cur_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['cur_exc_cur_id' => 'cur_id']],
            [['cur_exc_exc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Exchange::className(), 'targetAttribute' => ['cur_exc_exc_id' => 'exc_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cur_exc_cur_id' => Yii::t('app', 'Cur Exc Cur ID'),
            'cur_exc_exc_id' => Yii::t('app', 'Cur Exc Exc ID'),
            'cur_exc_code' => Yii::t('app', 'Cur Exc Code'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurExcCur()
    {
        return $this->hasOne(Currency::className(), ['cur_id' => 'cur_exc_cur_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurExcExc()
    {
        return $this->hasOne(Exchange::className(), ['exc_id' => 'cur_exc_exc_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\currency\query\CurrencyExchangeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\currency\query\CurrencyExchangeQuery(get_called_class());
    }
}
