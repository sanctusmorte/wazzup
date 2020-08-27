<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\{
    Controller,
    Response
};

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['support', 'description'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }
    
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Страница поддержки
     * @return string
     */

    public function actionSupport()
    {
        return $this->render('support');
    }

    /**
     * Страница описания
     * @return string
     */

    public function actionDescription()
    {
        return $this->render('description');
    }
}
