<?php

namespace common\models\account\query;

/**
 * This is the ActiveQuery class for [[\common\models\account\Exchange]].
 *
 * @see \common\models\account\Exchange
 */
class ExchangeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\account\Exchange[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\account\Exchange|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function freeExchange($localTime, $seconds) {
        return $this->where('exc_taked = FALSE OR exc_last_update >= :time', ['time' => $localTime - $seconds])->one();
    }
}
