<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/20/18
 * Time: 6:32 PM
 */

namespace bots;

use bots\models\BotThread;
use services\BotService;

require_once(__DIR__ . '/models/BotThread.php');
require_once(__DIR__ . '/services/BotService.php');
require_once(__DIR__ . '/services/LogService.php');
require_once(__DIR__ . '/services/ExchangeService.php');
require_once(__DIR__ . '/../common/models/utils/DateUtil.php');
require_once(__DIR__ . '/../common/models/ApiResult.php');


class VariationBot extends BotThread
{
    /**
     * @var int
     */
    public $iter = 1;
    /**
     * @var boolean
     */
    public $sincronize = false;

    public function doIt()
    {
        if ($this->genCandles()) {
            return true;
        }
        return false;

    }

    public function getBot()
    {
        return BotService::waitingVariationBot($this->exc_id);
    }

    public function genCandles()
    {
        return BotService::calcVariation($this->bot_ins_id);
    }

}