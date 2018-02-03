<?php

namespace common\models\constant;

use Yii;

/**
 * This is the model class for table "TypeBot".
 *
 * @property int $typ_bot_id
 * @property string $typ_bot_description
 *
 * @property \common\models\bot\Bot[] $bots
 */
class TypeBot extends \yii\db\ActiveRecord
{
    const EXCHANGE = 1;
    const SYNC = 1;
    const VOLUME = 1;
	const RESET = 2;
	const CANDLE = 3;
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'TypeBot';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['typ_bot_id', 'typ_bot_description'], 'required'],
			[['typ_bot_id'], 'integer'],
			[['typ_bot_description'], 'string', 'max' => 45],
			[['typ_bot_id'], 'unique'],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'typ_bot_id' => Yii::t('app', 'Typ Bot ID'),
			'typ_bot_description' => Yii::t('app', 'Typ Bot Description'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBots()
	{
		return $this->hasMany(\common\models\bot\Bot::className(), ['bot_typ_bot_id' => 'typ_bot_id']);
	}

	/**
	 * @inheritdoc
	 * @return \common\models\constant\query\TypeBotQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \common\models\constant\query\TypeBotQuery(get_called_class());
	}
}