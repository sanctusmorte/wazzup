<?php
namespace app\controllers;

use Yii;
use yii\filters\{
    AccessControl,
    VerbFilter
};
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

    public function behaviors() 
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'calculate' => ['post'],
                    'save' => ['post'],
                    'tracking' => ['get'],
                    'shipment-save' => ['post'],
                    'print' => ['post']
                ],
            ],
        ];
    }

    /**
     * Рассчет стоимости доставки 
     * 
     * @return array
     */

    public function actionCalculate(): array
    {
        return $this->deliveryService->calculate();
    }

    /**
     * Передача заказа в Logsis
     * 
     * @return array
     */

    public function actionSave(): array
    {
        return $this->deliveryService->save();
    }

    /**
     * Обмен статусов заказов из Logsis в retailCRM
     * 
     * @return boolean
     */

    public function actionTracking(): bool
    {
        return $this->deliveryService->tracking();
    }

    /**
     * Создание заявки на забор
     * 
     * @return array
     */

    public function actionShipmentSave(): array 
    {
        return $this->deliveryService->shipmentSave();
    }

    /**
     * Печатные формы службы доставки
     * 
     * @return
     */

    public function actionPrint()
    {
        Yii::info('print: ' . print_r(Yii::$app->request->post(), true));

        return [
            'success' => false,
            'errorMsg' => 'test print'
        ];
    }
}