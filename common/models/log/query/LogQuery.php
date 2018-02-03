<?php

namespace common\models\log\query;

/**
 * This is the ActiveQuery class for [[\common\models\log\Log]].
 *
 * @see \common\models\log\Log
 */
class LogQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\log\Log[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\log\Log|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
