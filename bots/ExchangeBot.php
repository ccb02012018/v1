<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/20/18
 * Time: 6:32 PM
 */

namespace bots;

use common\models\utils\DateUtil;
use services\BotService;
use services\LogService;

require_once(__DIR__ . '/Sync.php');
require_once(__DIR__ . '/services/BotService.php');
require_once(__DIR__ . '/services/LogService.php');
require_once(__DIR__ . '/../common/models/utils/DateUtil.php');
require_once(__DIR__ . '/../common/models/ApiResult.php');

class ExchangeBot extends \Worker
{
    /**
     * @var int
     */
    private $bot_id;
    /**
     * @var int
     */
    protected $exchange_id;

    /**
     *
     */
    public function run()
    {
        try {
            LogService::writeSimpleLog('Comienza Bot exchange');

            $this->bot_id = BotService::waitingExchangeBot();

            if ($this->bot_id == null) {
                LogService::writeSimpleLog('Sin bot exchange');
                return;
            }

            $lastWakeUp = DateUtil::getLocalTime();
            $seconds = BotService::startBot($this->bot_id);

            if ($seconds == -1) {
                LogService::writeSimpleLog('Sin segundos');
                return;
            }

            $sync = new Sync();

            //TODO SE DEBE ELIMINAR, SOLO PARA PRUEBAS (CONTADOR $i)
            $i = 0;
            do {
                while ($this->findSyncBot($sync)) {
                    continue;
                }

                if (!BotService::esValido($this->bot_id)) {
                    break;
                }

                $lastWakeUp = BotService::wait($this->bot_id, $lastWakeUp);
                $i++;
            } while ($i < 500);
        } catch (\Exception $e) {
            LogService::writeSimpleLog($e->getMessage());
        } catch (\Throwable $e) {
            LogService::writeSimpleLog($e->getMessage());
        }
        BotService::stop($this->bot_id);
        LogService::writeSimpleLog('Finaliza bot ' . $this->bot_id);
    }

    private function findSyncBot(&$sync)
    {
//        $syncBot = new SyncBot($this->exchange_id, $sync);
//        if ($syncBot->isValidBot()) {
//            $syncBot->start();
//            return true;
//        }
        return false;
    }

}