<?php
namespace app\controllers;

use app\services\WazzupService;
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

class WazzupController extends Controller
{
    private $wazzupService;

    public function __construct($id, Module $module, WazzupService $wazzupService, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->wazzupService = $wazzupService;
    }

    /**
     * Страница настроек модуля
     * 
     * @return string
     */

    public function actionIndex()
    {
        return $this->render('/setting/wazzup', [
            'wazzup' => 'awd'
        ]);
    }

    public function actionWebHook()
    {
        //$body = '{"Message" : {"ExternalId" : "111111", "Type" : "text", "Text" : "Тестовое сообщение wazzup dev-max"}, "User" : {"Firstname" : "TEst max", "external_id" : "333", "nickname" : "@test_max"}, "Channel" : 12}';
        //$this->wazzupService->sentMessageToRetailCrm('f46b6e96-a204-4498-b71c-2825380c488c', $body);
        $message = file_get_contents('php://input');
        if ($message !== null) {
            $this->wazzupService->handleMessageFromWazzup(json_decode($message)['messages']);
        }
    }
}