<?php

namespace api\controllers;

use yii\rest\ActiveController;

/**
 * Site controller
 */
class LogController extends ActiveController
{
    public $modelClass = 'common\models\log\Log';

    public function actionIndex()
    {
        return ['asdasd', 'qweqwe'];
    }

}
