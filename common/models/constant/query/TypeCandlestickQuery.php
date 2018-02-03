<?php

namespace common\models\constant\query;

/**
 * This is the ActiveQuery class for [[\common\models\constant\TypeCandlestick]].
 *
 * @see \common\models\constant\TypeCandlestick
 */
class TypeCandlestickQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return \common\models\constant\TypeCandlestick[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\constant\TypeCandlestick|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
