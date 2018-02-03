<?php
/**
 * Created by IntelliJ IDEA.
 * User: kor
 * Date: 1/23/18
 * Time: 1:34 AM
 */

namespace console\controllers;

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
        //$worker = new ExchangeBot();
        //$worker->start();
        echo 'terminadoooo';
    }
}