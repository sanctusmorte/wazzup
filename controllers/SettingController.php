<?php

namespace app\controllers;

use app\services\RetailTransportMgService;
use app\services\WazzupService;
use Yii;
use yii\web\{
    Controller,
    Response
};
use yii\bootstrap\ActiveForm;
use yii\base\Module;
use app\services\SettingService;
use app\models\{
    Setting
};

class SettingController extends Controller
{

    private $settingService, $retailTransportMgService, $wazzupService;

    /**
     * SettingController constructor.
     * @param $id
     * @param Module $module
     * @param SettingService $settingService
     * @param RetailTransportMgService $retailTransportMgService
     * @param array $config
     */
    public function __construct($id, Module $module, SettingService $settingService, WazzupService $wazzupService,
                                RetailTransportMgService $retailTransportMgService, $config = [])
    {
        $this->settingService = $settingService;
        $this->retailTransportMgService = $retailTransportMgService;
        $this->wazzupService = $wazzupService;


        parent::__construct($id, $module, $config);
    }

    public function actionTest()
    {
        $var = [

        json_decode('{"var" : "first_name"}', 1),

        " , здравствуйте!",
        "\n",
        "Вас приветствует интернет-магазин Epsom.pro🛀 Меня зовут (ИМЯ СОТРУДНИКА).",
        "\n",
        "Вы сделали заказ в нашем магазине, хочу обсудить доставку.",
        "\n",
        "Вы сделали заказ на нашем сайте, хочу обсудить доставку. Вам удобно будет забрать в бесплатном пункте самовывоза или курьера отправить? Стоимость доставки курьером 0000 руб.",
    ];

        Yii::error([$var, $var], 'wazzup_telegram_log');
        echo json_encode($var);
    }

    /**
     * Страница настроек модуля
     *
     * @return string
     */
    public function actionIndex()
    {
        $clientId = $this->settingService->getSettingId();

        $setting = $this->settingService->getSetting($clientId);

        Yii::$app->session->set('clientId', $setting->client_id);

        return $this->render('index', [
            'setting' => $setting
        ]);
    }

    public function actionSave()
    {

        $clientId = $this->settingService->getSettingId();

        $postData = Yii::$app->request->post();
        if (isset($postData['Setting']['channels'])) {
            $postData['Setting']['channels'] = json_encode($postData['Setting']['channels']);
        }

        $needSetting = null;

        if ($clientId !== null) {
            $existSetting = $this->settingService->getSettingById($clientId);

            if ($existSetting === null) {
                $newSetting = new Setting();
                $newSetting->client_id = $clientId;
                $newSetting->retail_crm_web_hook_uuid = $this->settingService->generateRetailCrmWebHookUuid();
                $newSetting->wazzup_web_hook_uuid = $this->settingService->generateWazzupWebHookUuid();
                $newSetting->is_active = 1;
                $needSetting = $newSetting;
                if ($newSetting->load($postData) && $newSetting->validate() && Yii::$app->request->post('submit')) {
                    $this->settingService->save($newSetting);
                    $this->retailTransportMgService->createChannelsInRetailCrm($newSetting);
                    //$this->retailTransportMgService->createTemplates($newSetting);
                    //$this->retailTransportMgService->updateTemplates($newSetting);
                    $this->wazzupService->putUrlWebHook($newSetting);
                    Yii::$app->session->set('clientId', $newSetting->client_id);
                    return $this->redirect(['/setting/index']);
                }
            } else {
                $needSetting = $existSetting;
                if ($existSetting->load($postData) && $existSetting->validate() && Yii::$app->request->post('submit')) {
                    $needSetting->is_active = 1;
                    $existSetting->retail_crm_web_hook_uuid = $this->settingService->generateRetailCrmWebHookUuid();
                    $existSetting->wazzup_web_hook_uuid = $this->settingService->generateWazzupWebHookUuid();
                    $this->settingService->save($existSetting);
                    $this->retailTransportMgService->createChannelsInRetailCrm($existSetting);
                    //$this->retailTransportMgService->createTemplates($existSetting);
                    //$this->retailTransportMgService->updateTemplates($existSetting);
                    $this->wazzupService->putUrlWebHook($existSetting);
                    return $this->redirect(['/setting/index']);
                }
            }
        } else {}

        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($needSetting);
    }


}
