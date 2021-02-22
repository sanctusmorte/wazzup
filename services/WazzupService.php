<?php


namespace app\services;

use app\helpers\RetailTransportMgHelper;
use app\helpers\WazzupHelper;
use app\models\Setting;
use Yii;

class WazzupService
{
    private $settingService;
    private $retailTransportMgHelper, $wazzupHelper;
    private $retailTransportMg;

    public function __construct(SettingService $settingService, RetailTransportMgHelper $retailTransportMgHelper,
                                WazzupHelper $wazzupHelper, RetailTransportMgService $retailTransportMg)
    {
        $this->settingService = $settingService;
        $this->retailTransportMgHelper = $retailTransportMgHelper;
        $this->wazzupHelper = $wazzupHelper;
        $this->retailTransportMg = $retailTransportMg;
    }

    public function putUrlWebHook($setting)
    {
        Yii::$app->wazzup->putUrlWebHook($setting);
    }

    public function handleMessageFromWazzup($wazzupMessages)
    {
        foreach ($wazzupMessages as $message) {
            if ($message['status'] === 99) {
                $this->retailTransportMg->sentMessageToRetailCrm($message);
            }
        }
    }

    public function sentMessageToWazzup($retailMessage)
    {
        $data = $this->settingService->getChannelInfoByChannelIdFromRetailCrm($retailMessage['data']['channel_id']);
        Yii::error($data, 'wazzup_telegram_log');
        if ($data !== null) {
            $body = $this->wazzupHelper->generateMessage($data, $retailMessage);
            Yii::$app->wazzup->sentMessage($data['wazzup_api_key'], $body);
        }
    }
}
