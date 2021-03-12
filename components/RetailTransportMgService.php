<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\web\ServerErrorHttpException;

use yii\helpers\{
    Url,
    Json
};

class RetailTransportMgService extends Component
{
    private $retailTransportMgService;

    public function __construct(\app\services\RetailTransportMgService $retailTransportMgService, array $config = [])
    {
        $this->retailTransportMgService = $retailTransportMgService;
        parent::__construct($config);
    }

    /**
     * @param $existSetting
     * @param $message
     */
    public  function handleMessageFromRetail($existSetting, $message)
    {
        $this->retailTransportMgService->handleMessageFromRetail($message, $existSetting);
    }
}
