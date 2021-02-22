<?php


namespace app\services;

use app\helpers\RetailTransportMgHelper;
use app\models\Setting;
use Yii;

class RetailTransportMgService
{
    private $settingService, $wazzupService;
    private $retailTransportMgHelper;

    public function __construct(SettingService $settingService, RetailTransportMgHelper $retailTransportMgHelper, WazzupService $wazzupService)
    {
        $this->settingService = $settingService;
        $this->wazzupService = $wazzupService;
        $this->retailTransportMgHelper = $retailTransportMgHelper;
    }

    public function createChannelsInRetailCrm($setting)
    {
        $needChannelsToSave = [];

        $channels = Yii::$app->wazzup->getChannels($setting);
        if ($channels !== false) {
            foreach ($channels as $channel) {
                $createChannel = Yii::$app->transport->createTransportInRetailCrm($setting, $channel);
                if (isset($createChannel['id'])) {
                    $needChannelsToSave[] = [
                        'external_id' => $channel['channelId'],
                        'id' => $createChannel['id'],
                        'channelType' => $channel['transport'],
                    ];
                }
            }
        }

        $this->setChannelsToSetting($setting, $needChannelsToSave);
    }

    public function sentMessageToRetailCrm($message)
    {
        $data = $this->settingService->getChannelDataByChannelId($message['channelId']);
        if ($data !== null) {
            $data['message'] = $this->retailTransportMgHelper->generateMessage($message, $data);
            Yii::$app->transport->sentMessageToRetailCrm($data);
        }
    }

    public function handleMessageFromRetail($retailMessage)
    {
       if ($retailMessage['type'] === 'message_sent') {
           $this->wazzupService->sentMessageToWazzup($retailMessage);
       }
        if ($retailMessage['type'] === 'message_read') {
            $this->setMessageReadInRetailCrm($retailMessage);
        }
    }

    private function setMessageReadInRetailCrm($retailMessage)
    {
        $data = $this->settingService->getChannelDataByChannelId($retailMessage['data']['channel_id']);
        if ($data !== null) {
            $body = [
                'Message' => [
                    'external_id' => $retailMessage['data']['external_message_id']
                ],
                'channel_id' => $retailMessage['data']['channel_id']
            ];
            Yii::$app->transport->sentMessageToRetailCrm($data, $body);
        }
    }

    private function setChannelsToSetting($setting, $needChannelsToSave)
    {
        $setting->wazzup_channels = json_encode($needChannelsToSave);
        $setting->save();
    }
}
