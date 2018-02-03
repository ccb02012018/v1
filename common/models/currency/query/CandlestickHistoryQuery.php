<?php

namespace common\models\currency\query;

/**
 * This is the ActiveQuery class for [[\common\models\currency\CandlestickHistory]].
 *
 * @see \common\models\currency\CandlestickHistory
 */
class CandlestickHistoryQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\currency\CandlestickHistory[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\currency\CandlestickHistory|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
