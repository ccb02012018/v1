<?php

namespace common\models\log;

use Yii;

/**
 * This is the model class for table "Log".
 *
 * @property int $log_id
 * @property string $log_message
 */
class Log extends \yii\db\ActiveRecord
{
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
            [['log_message'], 'required'],
            [['log_message'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'log_id' => Yii::t('app', 'Log ID'),
            'log_message' => Yii::t('app', 'Log Message'),
        ];
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
