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
     * Сохранение доставки
     */

    public function actionSave(): array
    {
        Yii::info(print_r(Yii::$app->request->post()), 'save_logsis');

        return [
            'success' => true,
            'errorMsg' => 'Ошибка сохранения'
        ];
    }

}