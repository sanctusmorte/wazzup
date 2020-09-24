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
        mail('baksheev.a.a@makeforu.ru' , 'TUT TEST IDET', print_r(Yii::$app->request->post(), true));

        return [
            'success' => false,
            'errorMsg' => '___ test'
        ];
    }

}