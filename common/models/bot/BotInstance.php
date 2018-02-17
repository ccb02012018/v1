<?php

namespace common\models\bot;

use common\models\log\Log;
use Yii;

/**
 * This is the model class for table "BotInstance".
 *
 * @property int $bot_ins_id
 * @property int $bot_ins_bot_id
 * @property int $bot_ins_start
 * @property int $bot_ins_end
 *
 * @property Bot $bot
 * @property Log[] $logs
 */
class BotInstance extends \yii\db\ActiveRecord
{
    /**
     * BotInstance constructor.
     * @param int $bot_ins_bot_id
     */
    public function __construct($bot_ins_bot_id = null)
    {
        $this->bot_ins_bot_id = $bot_ins_bot_id;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'BotInstance';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bot_ins_bot_id', 'bot_ins_start'], 'required'],
            [['bot_ins_bot_id', 'bot_ins_start', 'bot_ins_end'], 'integer'],
            [['bot_ins_bot_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bot::className(), 'targetAttribute' => ['bot_ins_bot_id' => 'bot_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'bot_ins_id' => Yii::t('common', 'Bot Ins ID'),
//            'bot_ins_bot_id' => Yii::t('common', 'Bot Ins Bot ID'),
//            'bot_ins_start' => Yii::t('common', 'Bot Ins Start'),
//            'bot_ins_end' => Yii::t('common', 'Bot Ins End'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBot()
    {
        return $this->hasOne(Bot::className(), ['bot_id' => 'bot_ins_bot_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLogs()
    {
        return $this->hasMany(Log::className(), ['log_bot_ins_id' => 'bot_ins_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\bot\query\BotInstanceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\bot\query\BotInstanceQuery(get_called_class());
    }
}
