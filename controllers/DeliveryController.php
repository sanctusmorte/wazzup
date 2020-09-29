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
     * Передача заказа в Logsis
     * 
     * @return array
     */

    public function actionSave(): array
    {
        // $q = '{"order":"7203","orderNumber":"7203A","site":"storeland-test","siteName":"storeland-test","store":{"id":8,"code":"test-moskva","name":"Тестовый для Москвы"},"customer":{"id":71996,"lastName":"Test","firstName":"Sasha","patronymic":"Test","phones":["79639570923"],"email":"baksheev.a.a@makeforu.ru"},"packages":[{"packageId":"1","weight":1000,"width":100,"length":100,"height":100,"items":[{"offerId":"372934","externalId":"149161308","name":"Go Hard футболка мужская (Размер: 48)","declaredValue":432,"cod":0,"quantity":1,"cost":432,"properties":{"article":{"name":"Артикул","value":"салют"}}},{"offerId":"376683","externalId":"226843077","name":"70х140 Полотенце махровое Туркменистан \\u0022Ашхабад\\u0022 (Цвет: Синий)","declaredValue":466,"cod":0,"quantity":1,"cost":466,"properties":{"article":{"name":"Артикул","value":"распродажа"}}}]}],"delivery":{"shipmentAddress":{"index":"121059","countryIso":"RU","region":"Москва город","regionId":55,"city":"Москва","cityId":4995,"cityType":"г.","street":"Бережковская","streetId":1778045,"streetType":"наб.","building":"20","house":"94","metro":"Киевская","text":"наб. Бережковская, д. 20, стр.\\/корп. 94, метро Киевская"},"deliveryAddress":{"index":"678144","countryIso":"RU","region":"Красноярский Край","regionId":10,"city":"Красноярск","cityId":1800,"cityType":"г.","street":"Ленина","streetId":1168584,"streetType":"ул.","building":"155","flat":"46","text":"ул. Ленина, д. 155, кв.\\/офис 46"},"withCod":false,"cod":0,"cost":399.96,"tariff":"1","payerType":"sender","deliveryDate":"2020-09-30","deliveryTime":{"from":"12:00","to":"14:00"},"extraData":{"is_single_cost":true,"is_partial_redemption":true,"is_fitting":false,"is_sms":true,"is_open":false,"is_additional_call":false,"is_return_doc":true,"is_skid":false,"is_payment_type":false,"is_assessed_value":false,"is_nds":false,"is_cargo_lift":false,"is_partial_return":false,"is_packaging":false}},"currency":"RUB"}';
        
        return $this->deliveryService->save();
    }

}