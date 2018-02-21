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
use services\ExchangeService;

require_once(__DIR__ . '/models/BotThread.php');
require_once(__DIR__ . '/services/BotService.php');
require_once(__DIR__ . '/services/LogService.php');
require_once(__DIR__ . '/services/ExchangeService.php');
require_once(__DIR__ . '/../common/models/utils/DateUtil.php');
require_once(__DIR__ . '/../common/models/ApiResult.php');


class SyncBot extends BotThread
{
    /**
     * @var int
     */
    public $iter = 0;

    public function doIt()
    {
        if ($this->sincronizeServer()) {
            return true;
        }
        return false;

    }

    public function getBot()
    {
        return BotService::waitingSyncBot($this->exc_id);
    }

    public function sincronizeServer()
    {
        return BotService::sincronizeServer($this->exc_id, $this->bot_ins_id);
    }

    public function afterStart()
    {
        return ExchangeService::taked($this->exc_id);
    }

    public function beforeStop()
    {
        return ExchangeService::untaked($this->exc_id);
    }
}