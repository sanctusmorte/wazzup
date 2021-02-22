<?php

namespace app\controllers;

use app\services\RetailTransportMg;
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
    /**
     * @var SettingService
     */
    private $settingService;

    /**
     * @var RetailTransportMg
     */
    private $transportService;

    /**
     * @var WazzupService
     */
    private $wazzupService;

    /**
     * SettingController constructor.
     * @param $id
     * @param Module $module
     * @param SettingService $settingService
     * @param RetailTransportMg $transportService
     * @param WazzupService $wazzupService
     * @param array $config
     */
    public function __construct($id, Module $module, SettingService $settingService,
                                RetailTransportMg $transportService, WazzupService $wazzupService, $config = [])
    {
        $this->settingService = $settingService;
        $this->transportService = $transportService;
        $this->wazzupService = $wazzupService;

        parent::__construct($id, $module, $config);
    }

    /**
     * Страница настроек модуля
     *
     * @return string
     */
    public function actionIndex()
    {

        //Yii::error(['awd'], 'wazzup_telegram_log');
        $clientId = $this->settingService->getSettingId();

        $setting = $this->settingService->getSetting($clientId);
        if ($setting->isNewRecord) {
            Yii::$app->session->set('clientId', $setting->client_id);
        }

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
                $newSetting->is_active = 1;
                $needSetting = $newSetting;
                if ($newSetting->load($postData) && $newSetting->validate() && Yii::$app->request->post('submit')) {
                    $this->settingService->save($newSetting);
                    $this->transportService->createChannelsInRetailCrm($newSetting);
                    $this->wazzupService->putUrlWebHook($newSetting);
                    Yii::$app->session->set('clientId', $newSetting->client_id);
                    return $this->redirect(['/setting/index']);
                }
            } else {
                $needSetting = $existSetting;
                if ($existSetting->load($postData) && $existSetting->validate() && Yii::$app->request->post('submit')) {
                    $needSetting->is_active = 1;
                    $this->settingService->save($existSetting);
                    $this->transportService->createChannelsInRetailCrm($existSetting);
                    $this->wazzupService->putUrlWebHook($existSetting);
                    return $this->redirect(['/setting/index']);
                }
            }
        } else {}

        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($needSetting);
    }


}
