<?php


namespace app\services;

use app\helpers\RetailTransportMgHelper;
use app\models\Setting;
use Yii;

class WazzupService
{
    private $settingService;
    private $retailTransportMgHelper;

    public function __construct(SettingService $settingService, RetailTransportMgHelper $retailTransportMgHelper)
    {
        $this->settingService = $settingService;
        $this->retailTransportMgHelper = $retailTransportMgHelper;
    }

    public function putUrlWebHook($setting)
    {
        $webHookEdit = Yii::$app->wazzup->putUrlWebHook($setting);
    }

    public function handleMessageFromWazzup($wazzupMessages)
    {
        foreach ($wazzupMessages as $message) {
            if ($message['status'] === 99) {
                //Yii::error($message, 'wazzup_telegram_log');
                $this->sentMessageToRetailCrm($message);
            }

//            if ($message['status'] === 3) {
//                $this->sentMessageToRetailCrm($message);
//            }
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
        $needSetting = null;
        $channelExternalId = null;
        $needChannelType = null;
        $channelid = $retailMessage['data']['channel_id'];
        $allSettings = Setting::findAll(['is_active' => 1]);

        foreach ($allSettings as $existSetting) {
            $existChannels = json_decode($existSetting->wazzup_channels, 1);
            //Yii::error([json_decode($existSetting->wazzup_channels, 1)], 'wazzup_telegram_log');
            if (count($existChannels) > 0) {
                foreach ($existChannels as $existChannel) {
                    if ($existChannel['id'] === $channelid) {
                        $needSetting = $existSetting;
                        $channelExternalId = $existChannel['external_id'];
                        $needChannelType = $existChannel['channelType'];
                        break;
                    }
                }
            }
        }

        //Yii::error($retailMessage, 'wazzup_telegram_log');

        if ($needSetting !== null and $channelExternalId !== null and $needChannelType !== null)  {
            $body = [
                'channelId' => $channelExternalId,
                'chatType' => $needChannelType,
                'chatId' => $retailMessage['data']['external_user_id'],
                'text' => $retailMessage['data']['content']
            ];
            Yii::$app->wazzup->sentMessage($needSetting, $body);
        }
    }


}
