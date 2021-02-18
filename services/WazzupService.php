<?php


namespace app\services;

use app\models\Setting;
use Yii;

class WazzupService
{
    public function putUrlWebHook($setting)
    {
        $webHookEdit = Yii::$app->wazzup->putUrlWebHook($setting);
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
        $needChannelExternalId = $message['channelId'];
        $setting = Setting::find()->where(['like', 'wazzup_channels', '%' . $needChannelExternalId . '%', false])->one();

        if ($setting !== null) {
            $needChanneId = null;
            $existChannels = json_decode($setting->wazzup_csentMessagehannels, 1);

            foreach ($existChannels as $existChannel) {
                if ($existChannel['external_id'] === $needChannelExternalId) {
                    $needChanneId = $existChannel['id'];
                    break;
                }
            }

            if ($needChanneId !== null) {
                Yii::$app->transport->sentMessageToRetailCrm($setting, $message, $needChanneId);
            }

        }
    }

    public function sentMessageToWazzup($retailMessage)
    {
        $needSetting = null;
        $channelExternalId = null;
        $channelid = $retailMessage['data']['channel_id'];
        $allSettings = Setting::findAll(['is_active' => 1]);
        foreach ($allSettings as $existSetting) {
            $existChannels = json_decode($existSetting->wazzup_channels, 1);
            if (count($existChannels) > 0) {
                foreach ($existChannels as $existChannel) {
                    if ($existChannel['id'] === $channelid) {
                        $needSetting = $existSetting;
                        $channelExternalId = $existChannel['external_id'];
                        break;
                    }
                }
            }
        }

        if ($needSetting !== null and $channelExternalId !== null) {
            $body = [
                'channelId' => $channelExternalId,
                'chatType' => 'instagram',
                'chatId' => $retailMessage['data']['external_user_id'],
                'text' => $retailMessage['data']['content']
            ];
            Yii::$app->wazzup->sentMessage($needSetting, $body);
        }
    }
}
