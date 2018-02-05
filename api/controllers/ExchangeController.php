<?php

namespace api\controllers;

use yii\rest\ActiveController;

/**
 * Site controller
 */
class ExchangeController extends ActiveController
{
    public $modelClass = 'common\models\account e\Exchange';

    public function actionIndex()
    {
        return ['asdasd', 'qweqwe'];
    }

}
