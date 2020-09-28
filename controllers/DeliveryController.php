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
    private $deliveryService;

    public function __construct($id, Module $module, $config = [], DeliveryService $deliveryService)
    {
        $this->deliveryService = $deliveryService;

        parent::__construct($id, $module, $config);
    }

    /**
     * Рассчет стоимости доставки 
     * 
     * @return array
     */

    public function actionCalculate(): array
    {
        \Yii::info('Retail crm request: ' . var_export(Yii::$app->request->post(), true), 'crm_info');

        return $this->deliveryService->calculate();
    }

}