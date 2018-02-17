<?php

namespace common\models\bot\query;

/**
 * This is the ActiveQuery class for [[\common\models\bot\BotInstance]].
 *
 * @see \common\models\bot\BotInstance
 */
class BotInstanceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\bot\BotInstance[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\bot\BotInstance|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
