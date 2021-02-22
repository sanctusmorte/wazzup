<?php


namespace app\services;

use app\helpers\RetailTransportMgHelper;
use app\helpers\WazzupHelper;
use app\models\Setting;
use Yii;

class RetailTransportMgService
{
    private $settingService;
    private $retailTransportMgHelper;
    private $wazzupHelper;

    public function __construct(SettingService $settingService, RetailTransportMgHelper $retailTransportMgHelper, WazzupHelper $wazzupHelper)
    {
        $this->settingService = $settingService;
        $this->retailTransportMgHelper = $retailTransportMgHelper;
        $this->wazzupHelper = $wazzupHelper;
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

    /**
     * Обрабатываем сообщение из RetailCRM и проверяем тип ("type") сообщения
     * @param $retailMessage
     */
    public function handleMessageFromRetail($retailMessage)
    {
        //Yii::error($retailMessage, 'wazzup_telegram_log');
       if ($retailMessage['type'] === 'message_sent') {
           $this->sentMessageToWazzup($retailMessage);
       }

        if ($retailMessage['type'] === 'message_read') {
            $this->setMessageReadInRetailCrm($retailMessage);
        }
    }

    /**
     * Отправляем сообщение из RetailCRM в Wazzup
     * @param $retailMessage
     */
    private function sentMessageToWazzup($retailMessage)
    {
        $data = $this->settingService->getChannelInfoByChannelIdFromRetailCrm($retailMessage['data']['channel_id']);

        if ($data !== null) {
            $body = $this->wazzupHelper->generateMessage($data, $retailMessage);
            Yii::$app->wazzup->sentMessage($data['wazzup_api_key'], $body);
        }
    }

    /**
     * Если сообщение от клиента было прочитано пользователем RetailCRM
     * То необходимо пометить его как прочитанное, таким образом в RetailCRM можно отметить все исходящие сообщения как прочитанные клиентом
     * @param $retailMessage
     */
    private function setMessageReadInRetailCrm($retailMessage)
    {
        Yii::error($retailMessage, 'wazzup_telegram_log');
        $data = $this->settingService->getChannelDataByChannelId($retailMessage['data']['channel_id']);

        //Yii::error($data, 'wazzup_telegram_log');

//        if ($data !== null) {
//            $body = [
//                'Message' => [
//                    'external_id' => $retailMessage['data']['external_message_id']
//                ],
//                'channel_id' => $retailMessage['data']['channel_id']
//            ];
//            Yii::$app->transport->sentMessageToRetailCrm($data, $body);
//        }
    }

    private function setChannelsToSetting($setting, $needChannelsToSave)
    {
        $setting->wazzup_channels = json_encode($needChannelsToSave);
        $setting->save();
    }
}
