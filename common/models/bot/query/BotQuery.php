<?php

namespace common\models\bot\query;

/**
 * This is the ActiveQuery class for [[\common\models\bot\Bot]].
 *
 * @see \common\models\bot\Bot
 */
class BotQuery extends \yii\db\ActiveQuery
{
    /**
     * @inheritdoc
     * @return \common\models\bot\Bot[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\bot\Bot|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @inheritdoc
     * @return \common\models\bot\Bot|array|null
     */
    public function waitingBot($typeBot, $exc_id)
    {
        return self::where(['bot_typ_bot_id' => $typeBot, 'acc_exc_id' => $exc_id])
            ->waiting()->orderBy('bot_id ASC')->one();
    }

    /**
     * @inheritdoc
     * @return \common\models\bot\Bot|array|null
     */
    public function waitingExchangeBot()
    {
        return self::exchange()->waiting()->active()->orderBy('bot_id ASC')->one();
    }

    /**
     * @inheritdoc
     * @return \common\models\bot\Bot|array|null
     */
    public function waitingSyncBot($exc_id)
    {
        return self::where(['bot_exc_id' => $exc_id])->sync()->waiting()->active()->orderBy('bot_id ASC')->one();
    }

    /**
     * @inheritdoc
     * @return \common\models\bot\Bot|array|null
     */
    public function waitingCandleBot($exc_id)
    {
        return self::where(['bot_exc_id' => $exc_id])->candle()->waiting()->active()->orderBy('bot_id ASC')->one();
    }

    /**
     * @inheritdoc
     * @return \common\models\bot\Bot|array|null
     */
    public function waitingCandlesGenBot($exc_id)
    {
        return self::where(['bot_exc_id' => $exc_id])->candlesgen()->waiting()->active()->orderBy('bot_id ASC')->one();
    }

    /**
     * @inheritdoc
     * @return \common\models\bot\Bot|array|null
     */
    public function waitingVariationBot($exc_id)
    {
        return self::where(['bot_exc_id' => $exc_id])->variation()->waiting()->active()->orderBy('bot_id ASC')->one();
    }

    /**
     * @inheritdoc
     * @return \common\models\bot\Bot|array|null
     */
    public function activeBot($typeBot)
    {
        return self::where(['bot_typ_bot_id' => $typeBot . 1])->active()->orderBy('bot_id ASC')->one();
    }

    private function running()
    {
        return self::andWhere(['bot_running' => true]);
    }

    private function waiting()
    {
        return self::andWhere(['bot_running' => false]);
    }

    private function active()
    {
        return self::andWhere(['bot_active' => true]);
    }

    private function inactive()
    {
        return self::andWhere(['bot_active' => false]);
    }

    private function volumen()
    {
        return self::andWhere(['bot_typ_bot_id' => \common\models\constant\TypeBot::VOLUME]);
    }

    private function exchange()
    {
        return self::andWhere(['bot_typ_bot_id' => \common\models\constant\TypeBot::EXCHANGE]);
    }

    private function sync()
    {
        return self::andWhere(['bot_typ_bot_id' => \common\models\constant\TypeBot::SYNC]);
    }

    private function candle()
    {
        return self::andWhere(['bot_typ_bot_id' => \common\models\constant\TypeBot::CANDLE]);
    }

    private function candlesgen()
    {
        return self::andWhere(['bot_typ_bot_id' => \common\models\constant\TypeBot::CANDLES_GEN]);
    }

    private function variation()
    {
        return self::andWhere(['bot_typ_bot_id' => \common\models\constant\TypeBot::VARIATION]);
    }
}
