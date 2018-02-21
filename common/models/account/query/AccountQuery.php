<?php

namespace common\models\account\query;

/**
 * This is the ActiveQuery class for [[\common\models\account\Account]].
 *
 * @see \common\models\account\Account
 */
class AccountQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \common\models\account\Account[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\account\Account|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\account\Account|array|null
     */
    public function byExchange($exc_id)
    {
        return parent::where(['acc_exc_id' => $exc_id])->orderBy('acc_limit_weight ASC')->one();
    }
}
