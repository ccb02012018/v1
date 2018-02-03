<?php

namespace common\models\currency\query;

/**
 * This is the ActiveQuery class for [[\common\models\currency\Candlestick]].
 *
 * @see \common\models\currency\Candlestick
 */
class CandlestickQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\currency\Candlestick[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\currency\Candlestick|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
