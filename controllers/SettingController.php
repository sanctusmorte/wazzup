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
// use app\models\{
//     Setting
// };

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
        return $this->render('index', [

        ]);
    }

}