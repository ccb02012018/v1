<?php

namespace common\models\currency;

use common\models\account\Exchange;
use common\models\constant\TypeCandlestick;
use Yii;

/**
 * This is the model class for table "CandlestickHistory".
 *
 * @property int $can_his_id
 * @property int $can_his_exc_id
 * @property int $can_his_cur_id
 * @property int $can_his_typ_can_id
 * @property int $can_his_open_time
 * @property string $can_his_open
 * @property string $can_his_high
 * @property string $can_his_low
 * @property string $can_his_close
 * @property int $can_his_close_time
 * @property int $can_his_volumen_int
 * @property int $can_his_volume_decimal
 * @property string $can_his_quote_asset_volume BTC volume
 * @property int $can_his_number_trades
 * @property string $can_his_tb_base_asset_volume taker buy Cantidad
 * @property string $can_his_tb_quote_asset_volume Taker buy precio
 * @property string $can_his_ignore algo
 *
 * @property Currency $canHisCur
 * @property Exchange $canHisExc
 * @property TypeCandlestick $canHisTypCan
 */
class CandlestickHistory extends \yii\db\ActiveRecord
{
	/**
	 * @inheritdoc
	 */
	public static function tableName()
	{
		return 'CandlestickHistory';
	}

	/**
	 * @inheritdoc
	 */
	public function rules()
	{
		return [
			[['can_his_exc_id', 'can_his_cur_id', 'can_his_typ_can_id', 'can_his_open_time', 'can_his_open', 'can_his_high', 'can_his_low', 'can_his_close', 'can_his_close_time', 'can_his_volumen_int', 'can_his_volume_decimal', 'can_his_quote_asset_volume', 'can_his_number_trades', 'can_his_tb_base_asset_volume', 'can_his_tb_quote_asset_volume'], 'required'],
			[['can_his_exc_id', 'can_his_cur_id', 'can_his_typ_can_id', 'can_his_open_time', 'can_his_close_time', 'can_his_volumen_int', 'can_his_volume_decimal', 'can_his_number_trades'], 'integer'],
			[['can_his_open', 'can_his_high', 'can_his_low', 'can_his_close', 'can_his_quote_asset_volume', 'can_his_tb_base_asset_volume', 'can_his_tb_quote_asset_volume', 'can_his_ignore'], 'number'],
			[['can_his_cur_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['can_his_cur_id' => 'cur_id']],
			[['can_his_exc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Exchange::className(), 'targetAttribute' => ['can_his_exc_id' => 'exc_id']],
			[['can_his_typ_can_id'], 'exist', 'skipOnError' => true, 'targetClass' => TypeCandlestick::className(), 'targetAttribute' => ['can_his_typ_can_id' => 'typ_can_id']],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels()
	{
		return [
			'can_his_id' => Yii::t('app', 'Can His ID'),
			'can_his_exc_id' => Yii::t('app', 'Can His Exc ID'),
			'can_his_cur_id' => Yii::t('app', 'Can His Cur ID'),
			'can_his_typ_can_id' => Yii::t('app', 'Can His Typ Can ID'),
			'can_his_open_time' => Yii::t('app', 'Can His Open Time'),
			'can_his_open' => Yii::t('app', 'Can His Open'),
			'can_his_high' => Yii::t('app', 'Can His High'),
			'can_his_low' => Yii::t('app', 'Can His Low'),
			'can_his_close' => Yii::t('app', 'Can His Close'),
			'can_his_close_time' => Yii::t('app', 'Can His Close Time'),
			'can_his_volumen_int' => Yii::t('app', 'Can His Volumen Int'),
			'can_his_volume_decimal' => Yii::t('app', 'Can His Volume Decimal'),
			'can_his_quote_asset_volume' => Yii::t('app', 'BTC volume'),
			'can_his_number_trades' => Yii::t('app', 'Can His Number Trades'),
			'can_his_tb_base_asset_volume' => Yii::t('app', 'taker buy Cantidad'),
			'can_his_tb_quote_asset_volume' => Yii::t('app', 'Taker buy precio'),
			'can_his_ignore' => Yii::t('app', 'algo'),
		];
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCanHisCur()
	{
		return $this->hasOne(Currency::className(), ['cur_id' => 'can_his_cur_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCanHisExc()
	{
		return $this->hasOne(Exchange::className(), ['exc_id' => 'can_his_exc_id']);
	}

	/**
	 * @return \yii\db\ActiveQuery
	 */
	public function getCanHisTypCan()
	{
		return $this->hasOne(TypeCandlestick::className(), ['typ_can_id' => 'can_his_typ_can_id']);
	}

	/**
	 * @inheritdoc
	 * @return \common\models\currency\query\CandlestickHistoryQuery the active query used by this AR class.
	 */
	public static function find()
	{
		return new \common\models\currency\query\CandlestickHistoryQuery(get_called_class());
	}
}