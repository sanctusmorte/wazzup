<?php
namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\{
    Controller,
    Response
};
use yii\bootstrap\ActiveForm;
use yii\base\Module;

use app\services\DeliveryService;
use app\controllers\ApiRestController;
use app\models\{
    Setting
};

class DeliveryController extends ApiRestController 
{


    /**
     * Рассчет стоимости доставки 
     * 
     * @return array
     */

    public function actionCalculate()
    {
        \Yii::info('Retail crm request: ' . var_export(Yii::$app->request->post(), true), 'crm_info');

        return [
            'success' => false,
            'errorMsg' => '___ test'
        ];
    }

}