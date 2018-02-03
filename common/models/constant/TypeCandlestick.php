<?php

namespace common\models\constant;

use Yii;

/**
 * This is the model class for table "TypeCandlestick".
 *
 * @property int $typ_can_id
 * @property string $typ_can_description
 * @property int $typ_can_milliseconds
 *
 * @property Bot[] $bots
 * @property Candlestick[] $candlesticks
 * @property CandlestickHistory[] $candlestickHistories
 */
class TypeCandlestick extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'TypeCandlestick';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['typ_can_description', 'typ_can_milliseconds'], 'required'],
            [['typ_can_milliseconds'], 'integer'],
            [['typ_can_description'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'typ_can_id' => Yii::t('app', 'Typ Can ID'),
            'typ_can_description' => Yii::t('app', 'Typ Can Description'),
            'typ_can_milliseconds' => Yii::t('app', 'Typ Can Milliseconds'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBots()
    {
        return $this->hasMany(Bot::className(), ['bot_typ_can_id' => 'typ_can_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCandlesticks()
    {
        return $this->hasMany(Candlestick::className(), ['can_typ_can_id' => 'typ_can_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCandlestickHistories()
    {
        return $this->hasMany(CandlestickHistory::className(), ['can_his_typ_can_id' => 'typ_can_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\constant\query\TypeCandlestickQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\constant\query\TypeCandlestickQuery(get_called_class());
    }
}
