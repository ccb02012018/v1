<?php

namespace api\controllers;

use common\models\ApiResult;
use common\models\log\Log;
use yii\rest\ActiveController;

/**
 * Site controller
 */
class LogController extends ActiveController
{
    public $modelClass = 'common\models\log\Log';


    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }

    public function actionCreate()
    {
        $log = new Log();
        $log->log_message = \Yii::$app->request->post('log_message');
        $log->save();

        return (new ApiResult($log->attributes))->getResponse();
    }


}
