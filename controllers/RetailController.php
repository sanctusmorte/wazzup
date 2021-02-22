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

class RetailController extends Controller
{
    private $wazzupService;


    public function __construct($id, Module $module, WazzupService $wazzupService, $config = [])
    {
        parent::__construct($id, $module, $config);

        $this->wazzupService = $wazzupService;
    }


    public function actionWebHook()
    {
        $data = file_get_contents('php://input');
        if ($data === null) {
            return http_response_code(200);
        } else {
            $message = json_decode($data, 1);

            if (isset($message['type'])) {
                $this->wazzupService->sentMessageToWazzup($message);

                $response = [
                    'success' => true,
                    'is_read' => true,
                ];

                echo json_encode($response);
            } else {
                return http_response_code(200);
            }
        }
    }
}
