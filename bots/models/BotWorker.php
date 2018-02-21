<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/20/18
 * Time: 6:32 PM
 */

namespace bots\models;

use common\models\utils\DateUtil;
use services\BotService;
use services\LogService;

class BotWorker extends \Worker
{
    /**
     * @var int
     */
    public $bot_id;
    /**
     * @var int
     */
    public $bot_ins_id;
    /**
     * @var int
     */
    public $exc_id;
    /**
     * @var int
     */
    public $cont = 0;

    /**
     *
     */
    public function run()
    {
        try {
            //LogService::writeSimpleLog('Comienza Bot exchange');

            $exchangeResult = BotService::waitingExchangeBot();

            if ($exchangeResult == null) {
                error_log('Sin bot exchange');
                return;
            }

            $this->bot_id = $exchangeResult['bot_id'];
            $this->exc_id = $exchangeResult['exc_id'];

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

            //TODO SE DEBE ELIMINAR, SOLO PARA PRUEBAS (CONTADOR $i)
            $i = 0;
            do {
                error_log('Vuelta EXCHANGE ' . $i . ' para el bot ' . $this->bot_id);
                var_dump('Vuelta EXCHANGE ' . $i . ' para el bot ' . $this->bot_id);
                if (!$this->doIt()) {
                    break;
                }

                if (!BotService::esValido($this->bot_id, $this->bot_ins_id)) {
                    break;
                }

                $lastWakeUp = BotService::wait($this->bot_id, $this->bot_ins_id, $lastWakeUp);
                if ($lastWakeUp == null) {
                    break;
                }

                $i++;
            } while ($i < $this->cont);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        } catch (\Throwable $e) {
            error_log($e->getMessage());
        }

        BotService::stop($this->bot_id, $this->bot_ins_id);
        LogService::writeSimpleLog($this->bot_ins_id, 'Finaliza bot ' . $this->bot_id);
    }

    public function doIt()
    {
        // HAS ALGO
        return false;
    }
}