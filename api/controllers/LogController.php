<?php

namespace api\controllers;

use common\custom\CArrayHelper;
use common\models\ApiResult;
use common\models\log\Log;
use common\models\Logger;
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
        try {
            $log = new Log(\Yii::$app->request->post('log_bot_ins_id'), \Yii::$app->request->post('log_message'));
            if (!$log->validate()) {
                Logger::writeFileLog(CArrayHelper::toString($log->getErrors()));
            } else {
                $log->save();
            }
            return (new ApiResult($log->attributes))->getResponse();
        } catch (\Exception $exception) {
            Logger::writeExceptionFileLog($exception);
            return (new ApiResult(-1, 1, $exception->getMessage()))->getResponse();
        }
    }

}
