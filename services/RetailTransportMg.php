<?php


namespace app\services;

use app\models\Setting;
use Yii;

class RetailTransportMg
{
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

    private function setChannelsToSetting($setting, $needChannelsToSave)
    {
        $setting->wazzup_channels = json_encode($needChannelsToSave);
        $setting->save();
    }
}
