<?php
namespace app\components;

use app\services\WazzupService;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\web\ServerErrorHttpException;

use yii\helpers\{
    Url,
    Json
};

class WazzupServiceComponent extends Component
{
    private $wazzupService;

    public function __construct(WazzupService $wazzupService, array $config = [])
    {
        $this->wazzupService = $wazzupService;
        parent::__construct($config);
    }

    /**
     * @param $messages
     * @param $existSetting
     */
    public  function handleMessageFromWazzup($messages, $existSetting)
    {
        $this->wazzupService->handleMessageFromWazzup($messages, $existSetting);
    }
}
