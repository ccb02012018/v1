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

    /**
     * @inheritdoc
     * @return \common\models\currency\Candlestick|array
     */
    public function byTime($exc_id, $cur_id, $typ_can_id, $start, $end)
    {
        return parent::where(['can_exc_id' => $exc_id, 'can_cur_id' => $cur_id, 'can_typ_can_id' => $typ_can_id])
            ->andWhere('can_open_time = :start AND can_close_time = :end', ['start' => $start, 'end' => $end])
            ->one();
    }
}
