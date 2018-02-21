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

    /**
     * @inheritdoc
     * @return \common\models\currency\CandlestickHistory[]|array
     */
    public function byTime($exc_id, $cur_id, $typ_can_id, $start, $end)
    {
        return parent::where(['can_his_exc_id' => $exc_id, 'can_his_cur_id' => $cur_id, 'can_his_typ_can_id' => $typ_can_id])
            ->andWhere('can_his_open_time >= :start AND can_his_close_time <= :end', ['start' => (int) $start, 'end' => (int) $end])
            ->all();
    }
}
