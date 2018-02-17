<?php

namespace common\models\log;

use common\models\bot\BotInstance;
use common\models\utils\DateUtil;
use Yii;

/**
 * This is the model class for table "Log".
 *
 * @property int $log_id
 * @property string $log_message
 * @property int $log_time_stamp
 * @property string $log_date_time
 * @property int $log_bot_ins_id
 *
 * @property BotInstance $botInstance
 */
class Log extends \yii\db\ActiveRecord
{
    /**
     * Log constructor.
     * @param int|null $bot_ins_bot_id
     * @param string|null $log_message
     */
    public function __construct($bot_ins_bot_id = null, $log_message = null)
    {
        $this->log_bot_ins_id = $bot_ins_bot_id;
        $this->log_message = $log_message;
        $this->setTime();
    }

    public function setTime()
    {
        $this->log_time_stamp = DateUtil::getLocalTime();
        $this->log_date_time = DateUtil::getDateTime();
    }
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'Log';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['log_message', 'log_time_stamp', 'log_bot_ins_id'], 'required'],
            [['log_time_stamp', 'log_bot_ins_id'], 'integer'],
            [['log_date_time'], 'safe'],
            [['log_message'], 'string', 'max' => 255],
            [['log_bot_ins_id'], 'exist', 'skipOnError' => true, 'targetClass' => BotInstance::className(), 'targetAttribute' => ['log_bot_ins_id' => 'bot_ins_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
//            'log_id' => Yii::t('common', 'Log ID'),
//            'log_message' => Yii::t('common', 'Log Message'),
//            'log_time_stamp' => Yii::t('common', 'Log Time Stamp'),
//            'log_date_time' => Yii::t('common', 'Log Date Time'),
//            'log_bot_ins_id' => Yii::t('common', 'Log Bot Ins ID'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBotInstance()
    {
        return $this->hasOne(BotInstance::className(), ['bot_ins_id' => 'log_bot_ins_id']);
    }

    /**
     * @inheritdoc
     * @return \common\models\log\query\LogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\log\query\LogQuery(get_called_class());
    }
}
