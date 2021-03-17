<?php
namespace app\controllers;

use app\jobs\WazzupJob;
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
            if ($existSetting !== null ) {
                $data = file_get_contents('php://input');
                if ($data === null or $data === '{"messages":[],"channels":[],"statuses":[]}') {
                    $responseCode = 200;
                } else {
                    $message = json_decode($data, 1);


                    if (isset($message['messages'])) {
                        if ($existSetting->is_active === 1 and $existSetting->is_freeze === 0) {
                            Yii::$app->queue->push(new WazzupJob($existSetting, $message['messages']));
                        }
                        $responseCode = 200;
                    } else if (isset($message['channelsList'])){
                        $this->retailTransportMgService->createChannelsInRetailCrm($existSetting);
                        $responseCode = 200;
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
