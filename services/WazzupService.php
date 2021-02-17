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
            $existChannels = $setting->wazzup_channels;
            Yii::error($existChannels, 'wazzup_telegram_log');
            foreach ($existChannels as $existChannel) {
                if ($existChannel['externalId'] === $needChannelExternalId) {
                    $needChanneId = $existChannel['id'];
                    break;
                }
            }
            
            if ($needChanneId !== null) {

                Yii::$app->transport->sentMessageToRetailCrm($setting, $message, $needChanneId);
            }

        }
    }
}