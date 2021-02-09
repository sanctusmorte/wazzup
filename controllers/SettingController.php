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

use app\services\SettingService;
use app\models\{
    Setting
};

class SettingController extends Controller 
{
    private $settingService;

    public function __construct($id, Module $module, $config = [], SettingService $settingService)
    {
        $this->settingService = $settingService;

        parent::__construct($id, $module, $config);
    }

    /**
     * Страница настроек модуля
     * 
     * @return string
     */

    public function actionIndex()
    {
        $clientId = $this->settingService->getSettingId();

        //echo $clientId;

        //Yii::$app->session->set('clientId', 'test');

        if ($clientId === null) {

        } else {
            $setting = $this->settingService->getSetting($clientId);
            return $this->render('index', [
                'setting' => $setting
            ]);
        }
    }

    public function actionSave()
    {

        $clientId = $this->settingService->getSettingId();

        if ($clientId !== null) {
            $setting = $this->settingService->getSettingById($clientId);
            if ($setting === null) {
                $NewSetting = new Setting();
                $NewSetting->client_id = $clientId;
                if ($NewSetting->load(Yii::$app->request->post()) && $NewSetting->validate() && Yii::$app->request->post('submit')) {
                    $this->settingService->save($NewSetting);
                    return $this->redirect(['/setting/index']);
                }
            }
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($NewSetting);
    }

}