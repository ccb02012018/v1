<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/23/18
 * Time: 1:34 AM
 */

namespace console\controllers;

use bots\CandleBot;
use bots\CandlesGeneratorBot;
use bots\ExchangeBot;
use bots\SyncBot;
use bots\VariationBot;
use yii\console\Controller;

class BotController extends Controller
{
    public $message;

    public function options($actionID)
    {
        return ['message'];
    }

    public function optionAliases()
    {
        return ['m' => 'message'];
    }

    public function actionIndex()
    {
        echo $this->message . "\n";
    }

    public function actionCorrerBot()
    {

        require_once(__DIR__ . '/../../bots/ExchangeBot.php');
        $worker = new ExchangeBot();
        $worker->start();
        //echo 'terminadoooo';
    }

    public function actionCorrerBotSync()
    {

        require_once(__DIR__ . '/../../bots/SyncBot.php');

        $worker = new SyncBot(1);
        if ($worker->isValidBot()) {
            $worker->run();
        }
        //echo 'terminadoooo';
    }

    public function actionCorrerBotCandle()
    {

        require_once(__DIR__ . '/../../bots/CandleBot.php');

        $worker = new CandleBot(1);
        if ($worker->isValidBot()) {
            $worker->run();
        }
        //echo 'terminadoooo';
    }

    public function actionCorrerBotCandles()
    {
        require_once(__DIR__ . '/../../bots/CandlesGeneratorBot.php');

        $worker = new CandlesGeneratorBot(1);
        if ($worker->isValidBot()) {
            $worker->run();
        }
        //echo 'terminadoooo';
    }

    public function actionCorrerBotVariation()
    {
        require_once(__DIR__ . '/../../bots/VariationBot.php');

        $worker = new VariationBot(1);
        if ($worker->isValidBot()) {
            $worker->run();
        }
        //echo 'terminadoooo';
    }
}