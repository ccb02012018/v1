<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/20/18
 * Time: 6:32 PM
 */

namespace bots\models;

use services\BotService;
use services\LogService;

class BotThread extends \Threaded
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
    public $iter = null;
    /**
     * @var int
     */
    public $lastWakeUp;
    /**
     * @var boolean
     */
    public $sincronize = false;

    public function isValidBot()
    {
        if ($this->bot_id != null) {
            return true;
        }
        return false;
    }

    public function __construct($exc_id)
    {
        $this->exc_id = $exc_id;
        $this->bot_id = $this->getBot();
    }

    public function getBot()
    {
        return null;
    }

    /**
     *
     */
    public function run()
    {
        try {
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

            if (!$this->afterStart()) {
                error_log('Error afterStart()');
                return;
            }

            $this->lastWakeUp = BotService::getLastWakeUp($this->bot_id, $this->bot_ins_id);

            $i = 0;
            do {
                if ($i > 0 || $this->sincronize) {
                    $this->lastWakeUp = BotService::wait($this->bot_id, $this->bot_ins_id, $this->lastWakeUp);
                    if ($this->lastWakeUp == null) {
                        break;
                    }
                }

                $valido = BotService::esValido($this->bot_id, $this->bot_ins_id);
                if ($valido == null) {
                    die;
                } elseif (!$valido) {
                    break;
                }

                if (!$this->doIt()) {
                    break;
                }

                if ($this->iter == null) {
                    $i = 1;
                    continue;
                }

                $i++;

                if ($i >= $this->iter) {
                    break;
                }
            } while (1);
        } catch (\Exception $e) {
            error_log($e->getMessage());
        } catch (\Throwable $e) {
            error_log($e->getMessage());
        }

        $this->beforeStop();

        BotService::stop($this->bot_id, $this->bot_ins_id);
        LogService::writeSimpleLog($this->bot_ins_id, 'Finaliza bot ' . $this->bot_id);
    }

    public function afterStart()
    {
        return true;
    }

    public function doIt()
    {
        return false;
    }

    public function beforeStop()
    {
        return true;
    }
}