<?php

namespace common\models\bot;

use common\custom\TimestampBehaviorBot;
use common\models\account\Account;
use common\models\account\Exchange;
use common\models\constant\TypeBot;
use common\models\constant\TypeCandlestick;
use common\models\currency\Currency;
use Yii;

/**
 * This is the model class for table "Bot".
 *
 * @property int $bot_id
 * @property string $bot_name
 * @property int $bot_exc_id
 * @property int $bot_last_update
 * @property int $bot_typ_bot_id
 * @property int $bot_typ_can_id
 * @property int $bot_cur_id Market currency
 * @property int $bot_sleep
 * @property int $bot_wake_up
 * @property boolean $bot_active
 * @property boolean $bot_running
 * @property string $bot_process_id
 * @property int $bot_acc_id
 *
 * @property Account $account
 * @property Currency $currency
 * @property Exchange $exchange
 * @property TypeBot $typeBot
 * @property TypeCandlestick $typeCandlestick
 * @property BotInstance[] $botInstances
 */
class Bot extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function behaviors () {
        return [
            TimestampBehaviorBot::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Bot';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bot_name', 'bot_exc_id', 'bot_typ_bot_id', 'bot_typ_can_id', 'bot_cur_id', 'bot_sleep', 'bot_active'], 'required'],
            [['bot_exc_id', 'bot_last_update', 'bot_typ_bot_id', 'bot_typ_can_id', 'bot_cur_id', 'bot_wake_up', 'bot_acc_id'], 'integer'],
            [['bot_active', 'bot_sleep', 'bot_running'], 'boolean'],
            [['bot_name', 'bot_process_id'], 'string', 'max' => 45],
            [['bot_acc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Account::className(), 'targetAttribute' => ['bot_acc_id' => 'acc_id']],
            [['bot_cur_id'], 'exist', 'skipOnError' => true, 'targetClass' => Currency::className(), 'targetAttribute' => ['bot_cur_id' => 'cur_id']],
            [['bot_exc_id'], 'exist', 'skipOnError' => true, 'targetClass' => Exchange::className(), 'targetAttribute' => ['bot_exc_id' => 'exc_id']],
            [['bot_typ_bot_id'], 'exist', 'skipOnError' => true, 'targetClass' => TypeBot::className(), 'targetAttribute' => ['bot_typ_bot_id' => 'typ_bot_id']],
            [['bot_typ_can_id'], 'exist', 'skipOnError' => true, 'targetClass' => TypeCandlestick::className(), 'targetAttribute' => ['bot_typ_can_id' => 'typ_can_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'bot_id' => Yii::t('common', 'Bot ID'),
//            'bot_name' => Yii::t('common', 'Bot Name'),
//            'bot_exc_id' => Yii::t('common', 'Bot Exc ID'),
//            'bot_last_update' => Yii::t('common', 'Bot Last Update'),
//            'bot_typ_bot_id' => Yii::t('common', 'Bot Typ Bot ID'),
//            'bot_typ_can_id' => Yii::t('common', 'Bot Typ Can ID'),
//            'bot_cur_id' => Yii::t('common', 'Market currency'),
//            'bot_sleep' => Yii::t('common', 'Bot Sleep'),
//            'bot_wake_up' => Yii::t('common', 'Bot Wake Up'),
//            'bot_active' => Yii::t('common', 'Bot Active'),
//            'bot_process_id' => Yii::t('common', 'Bot Process ID'),
//            'bot_acc_id' => Yii::t('common', 'Bot Acc ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(), ['acc_id' => 'bot_acc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCurrency()
    {
        return $this->hasOne(Currency::className(), ['cur_id' => 'bot_cur_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExchange()
    {
        return $this->hasOne(Exchange::className(), ['exc_id' => 'bot_exc_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeBot()
    {
        return $this->hasOne(TypeBot::className(), ['typ_bot_id' => 'bot_typ_bot_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTypeCandlestick()
    {
        return $this->hasOne(TypeCandlestick::className(), ['typ_can_id' => 'bot_typ_can_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBotInstance()
    {
        return $this->hasMany(BotInstance::className(), ['bot_ins_bot_id' => 'bot_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\bot\query\BotQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\bot\query\BotQuery(get_called_class());
    }
}
