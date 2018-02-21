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


class CandleBot extends BotThread
{
    /**
     * @var int
     */
    public $iter = null;
    /**
     * @var boolean
     */
    public $sincronize = true;

    public function doIt()
    {
        if ($this->getCandles()) {
            return true;
        }
        error_log('Falla ' . $this->bot_id);
        var_dump('Falla ' . $this->bot_id);
        return false;

    }

    public function getBot()
    {
        return BotService::waitingCandleBot($this->exc_id);
    }

    public function getCandles()
    {
        return BotService::getCandles($this->bot_ins_id);
    }

}