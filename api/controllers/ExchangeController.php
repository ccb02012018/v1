<?php

namespace api\controllers;

use common\models\account\Exchange;
use common\models\ApiResult;
use common\models\Logger;
use yii\rest\ActiveController;

/**
 * Site controller
 */
class ExchangeController extends ActiveController
{
    public $modelClass = 'common\models\account\Exchange';

    public function actionTaked()
    {
        try {
            $exc_id = \Yii::$app->request->post('exc_id');
            $exchange = Exchange::findOne($exc_id);

            $exchange->exc_taked = true;
            $exchange->update();

        } catch (\Exception $e) {
            Logger::writeExceptionFileLog($e);
            return (new ApiResult(null, $e->getLine(), $e->getMessage()))->getResponse();
        }

        return (new ApiResult(true))->getResponse();
    }

    public function actionUntaked()
    {
        try {
            $exc_id = \Yii::$app->request->post('exc_id');
            $exchange = Exchange::findOne($exc_id);

            $exchange->exc_taked = false;
            $exchange->update();

        } catch (\Exception $e) {
            Logger::writeExceptionFileLog($e);
            return (new ApiResult(null, $e->getLine(), $e->getMessage()))->getResponse();
        }

        return (new ApiResult(true))->getResponse();
    }

}
