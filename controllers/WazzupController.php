<?php
namespace app\controllers;

use app\services\RetailTransportMgService;
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
    private $retailTransportMgService;

    public function __construct($id, Module $module, WazzupService $wazzupService, RetailTransportMgService $retailTransportMgService, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->wazzupService = $wazzupService;
        $this->retailTransportMgService = $retailTransportMgService;
    }

    /**
     * @param $uuid
     * @return int
     */
    public function actionWebHook($uuid)
    {
        $responseCode = 500;

        if ($uuid !== null and $uuid !== "") {
            $existSetting = Setting::find()->where(['wazzup_web_hook_uuid' => $uuid])->one();
            if ($existSetting !== null) {
                $data = file_get_contents('php://input');
                if ($data === null or $data === '{"messages":[],"channels":[],"statuses":[]}') {
                    $responseCode = 200;
                } else {
                    $message = json_decode($data, 1);

                    Yii::error($message, 'wazzup_telegram_log');

                    if (isset($message['messages'])) {
                        //Yii::error($message, 'wazzup_telegram_log');
                        $this->wazzupService->handleMessageFromWazzup($message['messages'], $existSetting);
                        $responseCode = 200;
                    } else if (isset($message['channelsList'])){
                       // Yii::error($message, 'wazzup_telegram_log');
                        $this->retailTransportMgService->createChannelsInRetailCrm($existSetting);
                    } else {
                        $responseCode = 200;
                    }

                }
            }
        } else {
            return http_response_code(403);
        }

        return $responseCode;
    }
}
