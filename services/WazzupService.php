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

    public function __construct(SettingService $settingService, RetailTransportMgHelper $retailTransportMgHelper, WazzupHelper $wazzupHelper)
    {
        $this->settingService = $settingService;
        $this->retailTransportMgHelper = $retailTransportMgHelper;
        $this->wazzupHelper = $wazzupHelper;
    }

    public function putUrlWebHook($setting)
    {
        Yii::$app->wazzup->putUrlWebHook($setting);
    }

    public function handleMessageFromWazzup($wazzupMessages)
    {
        foreach ($wazzupMessages as $message) {
            if ($message['status'] === 99) {
                $this->sentMessageToRetailCrm($message);
            }
        }
    }

    public function sentMessageToRetailCrm($message)
    {
        $data = $this->settingService->getChannelIdByChannelIdFromWazzup($message['channelId']);
        if ($data !== null) {
            $data['message'] = $this->retailTransportMgHelper->generateMessage($message, $data);
            Yii::$app->transport->sentMessageToRetailCrm($data);
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
