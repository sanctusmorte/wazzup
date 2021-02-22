<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\{
    Controller,
    Response
};
use RetailCrm\Common\Exception;
use RetailCrm\Mg\Bot\Client;
use RetailCrm\Mg\Bot\Model\Request\DialogAssignRequest;

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
        var_dump(phpinfo());

//        $client = new Client('https://mg-s1.retailcrm.pro', '2844d55a3b95d43ebdef57e219207f22b029b5425c53214f1c19d24d72bfe19cba88', true);
//
//        try {
//            $request = new DialogAssignRequest();
//            $request->setDialogId(999);
//            $request->setUserId(1);
//
//            /* @var \RetailCrm\Mg\Bot\Model\Response\AssignResponse $response */
//            $response = $client->dialogAssign($request);
//            var_dump($response);
//        } catch (Exception\LimitException | Exception\InvalidJsonException | Exception\UnauthorizedException $exception) {
//            echo $exception->getMessage();
//        }

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
