<?php

namespace common\models\currency;

use common\models\account\Exchange;
use common\models\constant\TypeCandlestick;
use Yii;

/**
 * This is the model class for table "Candlestick".
 *
 * @property int $can_id
 * @property int $can_exc_id
 * @property int $can_cur_id
 * @property int $can_typ_can_id
 * @property int $can_open_time
 * @property string $can_open
 * @property string $can_high
 * @property string $can_low
 * @property string $can_close
 * @property int $can_close_time
 * @property int $can_volume
 * @property double $can_quote_asset_volume BTC volume
 * @property int $can_number_trades
 * @property double $can_tb_base_asset_volume taker buy Cantidad
 * @property double $can_tb_quote_asset_volume Taker buy precio
 * @property string $can_ignore algo
 * @property string $can_previous_volume algo
 * @property string $can_variation_volume algo
 * @property string can_previous_price algo
 * @property string can_variation_price algo
 *
 * @property Currency $canCur
 * @property Exchange $canExc
 * @property TypeCandlestick $canTypCan
 */
class Candlestick extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'Candlestick';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['can_exc_id', 'can_cur_id', 'can_typ_can_id', 'can_open_time', 'can_open', 'can_high', 'can_low', 'can_close', 'can_close_time', 'can_volume', 'can_quote_asset_volume', 'can_number_trades', 'can_tb_base_asset_volume', 'can_tb_quote_asset_volume'], 'required'],
			[['can_exc_id', 'can_cur_id', 'can_typ_can_id', 'can_open_time', 'can_close_time', 'can_volume', 'can_number_trades'], 'integer'],
			[['can_open', 'can_high', 'can_low', 'can_close', 'can_quote_asset_volume', 'can_tb_base_asset_volume', 'can_tb_quote_asset_volume', 'can_ignore'], 'number'],
			[['can_cur_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['can_cur_id' => 'cur_id']],
			[['can_exc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Exchange::className(), 'targetAttribute' => ['can_exc_id' => 'exc_id']],
			[['can_typ_can_id'], 'exist', 'skipOnError' => true, 'targetClass' => TypeCandlestick::className(), 'targetAttribute' => ['can_typ_can_id' => 'typ_can_id']],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'can_id' => Yii::t('app', 'Can ID'),
			'can_exc_id' => Yii::t('app', 'Can Exc ID'),
			'can_cur_id' => Yii::t('app', 'Can Cur ID'),
			'can_typ_can_id' => Yii::t('app', 'Can Typ Can ID'),
			'can_open_time' => Yii::t('app', 'Can Open Time'),
			'can_open' => Yii::t('app', 'Can Open'),
			'can_high' => Yii::t('app', 'Can High'),
			'can_low' => Yii::t('app', 'Can Low'),
			'can_close' => Yii::t('app', 'Can Close'),
			'can_close_time' => Yii::t('app', 'Can Close Time'),
			'can_volume' => Yii::t('app', 'Can Volumen Int'),
			'can_quote_asset_volume' => Yii::t('app', 'BTC volume'),
			'can_number_trades' => Yii::t('app', 'Can Number Trades'),
			'can_tb_base_asset_volume' => Yii::t('app', 'taker buy Cantidad'),
			'can_tb_quote_asset_volume' => Yii::t('app', 'Taker buy precio'),
			'can_ignore' => Yii::t('app', 'algo'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCanCur()
	{
		return $this->hasOne(Currency::className(), ['cur_id' => 'can_cur_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCanExc()
	{
		return $this->hasOne(Exchange::className(), ['exc_id' => 'can_exc_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCanTypCan()
	{
		return $this->hasOne(TypeCandlestick::className(), ['typ_can_id' => 'can_typ_can_id']);
	}

	/**
	 * @inheritdoc
	 * @return \common\models\currency\query\CandlestickQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \common\models\currency\query\CandlestickQuery(get_called_class());
	}
}