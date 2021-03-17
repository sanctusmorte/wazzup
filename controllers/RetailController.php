<?php
namespace app\controllers;

use app\jobs\FileJob;
use app\jobs\RetailJob;
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

class RetailController extends Controller
{
    private $retailTransportMgService;


    public function __construct($id, Module $module, RetailTransportMgService $retailTransportMgService, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->retailTransportMgService = $retailTransportMgService;
    }

    /**
     * На этот контроллер приходят сообщения из RetailCRM
     * @param $uuid
     * @return int
     */
    public function actionWebHook($uuid)
    {
        if ($uuid !== null and $uuid !== "") {

            $existSetting = Setting::find()->where(['retail_crm_web_hook_uuid' => $uuid])->one();

            if ($existSetting !== null and $existSetting->is_active === true and $existSetting->is_freeze === false) {

                $data = file_get_contents('php://input');

                if ($data === null) {
                    return http_response_code(200);
                } else {
                    $message = json_decode($data, 1);

                    if (isset($message['type'])) {

                        Yii::$app->queue->push(new RetailJob($existSetting, $message));
                        $response = [
                            'success' => true,
                        ];
                        echo json_encode($response);
                        exit;

                    } else {
                        return http_response_code(200);
                    }
                }
            } else {
                return http_response_code(500);
            }
        } else {
            return http_response_code(500);
        }
    }
}
