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

class RetailController extends Controller
{
    private $retailTransportMgService;


    public function __construct($id, Module $module, RetailTransportMgService $retailTransportMgService, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->retailTransportMgService = $retailTransportMgService;
    }


    public function actionWebHook2()
    {
        $data = file_get_contents('php://input');
        if ($data === null) {
            return http_response_code(200);
        } else {
            $message = json_decode($data, 1);

            Yii::error(['сообщение из срмки' => $message], 'wazzup_telegram_log');

            if (isset($message['type'])) {
                //$this->retailTransportMgService->handleMessageFromRetail($message);

                $response = [
                    'success' => true,
                ];

                echo json_encode($response);
            } else {
                return http_response_code(200);
            }
        }
    }
}
