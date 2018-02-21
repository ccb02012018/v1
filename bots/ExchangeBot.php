<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/20/18
 * Time: 6:32 PM
 */

namespace bots;

use bots\models\BotWorker;

require_once(__DIR__ . '/SyncBot.php');
require_once(__DIR__ . '/services/BotService.php');
require_once(__DIR__ . '/services/LogService.php');
require_once(__DIR__ . '/../common/models/utils/DateUtil.php');
require_once(__DIR__ . '/../common/models/ApiResult.php');
require_once(__DIR__ . '/models/BotWorker.php');

class ExchangeBot extends BotWorker
{
    public $cont = 5;

    private $syncBots = [];

    public function doIt()
    {
        try {
            return $this->findSyncBot();
        } catch (\Exception $exception) {
            error_log('Error: ' . $exception->getMessage());
            return false;
        }
    }

    public function findSyncBot()
    {
        $syncBot = new SyncBot($this->exc_id);
        if ($syncBot->isValidBot()) {
            $syncBot->run();
            error_log('prueba');
            array_push($this->syncBots, $syncBot);
            return true;
        }
        return false;
    }

}