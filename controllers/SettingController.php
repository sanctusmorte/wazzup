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
        $clientId = Yii::$app->request->post('clientId') ?? Yii::$app->session->get('clientId');

        // $clientId = 'MaXQWPb8-JLZI_TmMIM3jSijvXyLDK77';

        if ($clientId) {
            Yii::$app->session->set('clientId', $clientId);
        }
        
        $setting = $this->settingService->getSetting($clientId ?? '');
        if (!$setting->isNewRecord) $this->settingService->backgroundUpdateSetting($setting);

        return $this->render('index', [
            'setting' => $setting
        ]);
    }

    /**
     * Сохранение настроек модуля
     * 
     * @return string
     */

    public function actionSave()
    {
        if ($setting_id = Yii::$app->request->post('Setting')['id']) {
            $setting = $this->settingService->getSettingById($setting_id);
        } else {    
            $setting = new Setting();
        }

        if ($setting->load(Yii::$app->request->post()) && $setting->validate() && Yii::$app->request->post('submit')) {
            $this->settingService->save($setting);

            return $this->redirect(['/setting/index']);
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return ActiveForm::validate($setting);
    }

}