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
        $data = file_get_contents('php://input');
        Yii::error($data, 'wazzup_telegram_log');
        if ($data === null or $data === '{"messages":[],"channels":[],"statuses":[]}') {
            return http_response_code(200);
        } else {
            $message = json_decode($data, 1);
            if (isset($message['messages'])) {
                Yii::error($message, 'wazzup_telegram_log');
                $this->wazzupService->handleMessageFromWazzup($message['messages']);
            } else {
                return http_response_code(200);
            }
        }
    }
}
