<?php

namespace backend\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class BotController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['contentNegotiator']['formats']['text/html'] = Response::FORMAT_HTML;
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        return ['asdasd', 'qweqwe'];
    }
}
