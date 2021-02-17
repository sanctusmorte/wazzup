<?php


namespace app\services;

use app\models\Setting;
use Yii;

class WazzupService
{
    public function putUrlWebHook($setting)
    {
        $webHookEdit = Yii::$app->wazzup->putUrlWebHook($setting);
        var_dump($webHookEdit);
    }

    public function handleMessageFromWazzup($message)
    {
        if ($message['status'] === 99) {
            $this->sentMessageToRetailCrm($message);
        }
    }

    public function sentMessageToRetailCrm($message)
    {
        $needChannelExternalId = $message['channelId'];
        $setting = Setting::find()->where(['like', 'wazzup_channels', '%' . $needChannelExternalId . '%', false])->one();
        if ($setting !== null) {
            $needChanneId = null;
            $existChannels = $setting->wazzup_channels;
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