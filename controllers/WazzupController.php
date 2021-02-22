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
     * @param $uuid
     * @return int
     */
    public function actionWebHook($uuid)
    {
        $responseCode = 404;

        if ($uuid !== null and $uuid !== "") {
            $existSetting = Setting::find()->where(['wazzup_web_hook_uuid' => $uuid])->one();
            if ($existSetting !== null) {
                $data = file_get_contents('php://input');
                if ($data === null or $data === '{"messages":[],"channels":[],"statuses":[]}') {
                    $responseCode = 200;
                } else {
                    $message = json_decode($data, 1);
                    if (isset($message['messages'])) {
                        $this->wazzupService->handleMessageFromWazzup($message['messages'], $existSetting);
                        $responseCode = 200;
                    } else {
                        $responseCode = 200;
                    }
                }
            }
        }

        return $responseCode;
    }
}
