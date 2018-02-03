<?php

namespace common\models\account;

use common\models\bot\Bot;
use Yii;

/**
 * This is the model class for table "Account".
 *
 * @property int $acc_id
 * @property int $acc_exc_id
 * @property string $acc_key
 * @property string $acc_secret
 * @property string $acc_name
 * @property int $acc_limit_weight
 *
 * @property Exchange $exchange
 * @property Bot[] $bots
 */
class Account extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Account';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['acc_exc_id', 'acc_key', 'acc_secret', 'acc_limit_weight'], 'required'],
			[['acc_exc_id', 'acc_limit_weight'], 'integer'],
			[['acc_key', 'acc_secret'], 'string', 'max' => 255],
			[['acc_name'], 'string', 'max' => 45],
			[['acc_exc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Exchange::className(), 'targetAttribute' => ['acc_exc_id' => 'exc_id']],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'acc_id' => Yii::t('app', 'Acc ID'),
			'acc_exc_id' => Yii::t('app', 'Acc Exc ID'),
			'acc_key' => Yii::t('app', 'Acc Key'),
			'acc_secret' => Yii::t('app', 'Acc Secret'),
			'acc_name' => Yii::t('app', 'Acc Name'),
			'acc_limit_weight' => Yii::t('app', 'Acc Limit Weight'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getExchange()
	{
		return $this->hasOne(Exchange::className(), ['exc_id' => 'acc_exc_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getBots()
	{
		return $this->hasMany(Bot::className(), ['bot_acc_id' => 'acc_id']);
	}

	/**
	 * @inheritdoc
	 * @return \common\models\account\query\AccountQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \common\models\account\query\AccountQuery(get_called_class());
	}
}