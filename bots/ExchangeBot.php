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
    private $bot_ins_id;
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
            //LogService::writeSimpleLog('Comienza Bot exchange');

            $this->bot_id = BotService::waitingExchangeBot();

            if ($this->bot_id == null) {
                error_log('Sin bot exchange');
                return;
            }

            $lastWakeUp = DateUtil::getLocalTime();
            $startBtot = BotService::startBot($this->bot_id);
            if ($startBtot == null) {
                return;
            }
            $seconds = $startBtot['seconds'];
            $this->bot_ins_id = $startBtot['bot_ins_id'];

            if ($seconds == -1) {
                error_log('Sin segundos');
                return;
            }

            $sync = new Sync();

            //TODO SE DEBE ELIMINAR, SOLO PARA PRUEBAS (CONTADOR $i)
            $i = 0;
            do {
                while ($this->findSyncBot($sync)) {
                    continue;
                }

                if (!BotService::esValido($this->bot_id, $this->bot_ins_id)) {
                    break;
                }

                $lastWakeUp = BotService::wait($this->bot_id, $this->bot_ins_id, $lastWakeUp);
                if ($lastWakeUp == null) {
                    break;
                }
                $i++;
            } while ($i < 2);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        } catch (\Throwable $e) {
            error_log($e->getMessage());
        }

        BotService::stop($this->bot_id, $this->bot_ins_id);

        LogService::writeSimpleLog($this->bot_ins_id, 'Finaliza bot ' . $this->bot_id);
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