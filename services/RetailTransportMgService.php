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
     * @param $existSetting
     */
    public function handleMessageFromRetail(array $retailMessage, $existSetting)
    {

       if ($retailMessage['type'] === 'message_sent') {

           if (!isset($retailMessage['data']['items'])) {
               $this->sentMessageToWazzup($retailMessage, $existSetting);
           } else {
               $this->handleFiles($retailMessage, $existSetting);
           }

       }

        if ($retailMessage['type'] === 'message_read') {
            $this->setMessageReadInRetailCrm($retailMessage, $existSetting);
        }
    }

    private function handleFiles($retailMessage, $existSetting)
    {
        if (count($retailMessage['data']['items']) > 0) {
            foreach ($retailMessage['data']['items'] as $file) {
                if (isset($file['height']) and isset($file['width'])) {

                    $findFileUrl = Yii::$app->transport->getFileUrl($existSetting, $file['id']);
                    //Yii::error($findFileUrl, 'wazzup_telegram_log');
                    if ($findFileUrl !== null) {
                        $this->sentImageToWazzup($retailMessage, $existSetting, $findFileUrl);
                    }
                }
            }
        }
    }

    private function sentImageToWazzup(array $retailMessage, $existSetting, string $imageUrl)
    {
        $data = $this->settingService->getChannelInfoByChannelIdFromRetailCrm($retailMessage['data']['channel_id'], $existSetting);

        if ($data !== null) {
            $body = $this->wazzupHelper->generateMessageForImage($data, $retailMessage, $imageUrl);

            $result = Yii::$app->wazzup->sentMessage($existSetting->wazzup_api_key, $body);
        }
    }

    /**
     * Отправляем сообщение из RetailCRM в Wazzup
     * @param $retailMessage
     * @param $existSetting
     */
    private function sentMessageToWazzup($retailMessage, $existSetting)
    {
        $data = $this->settingService->getChannelInfoByChannelIdFromRetailCrm($retailMessage['data']['channel_id'], $existSetting);
        //Yii::error($retailMessage['data']['channel_id'], 'wazzup_telegram_log');

        if ($data !== null) {
            $body = $this->wazzupHelper->generateMessage($data, $retailMessage);
            $response = Yii::$app->wazzup->sentMessage($existSetting->wazzup_api_key, $body);

            if ($existSetting->wazzup_web_hook_uuid === 'FuzoV68F4Caqolhsoqh8AmoWXaV1A4YV') {
                Yii::error($response, 'wazzup_telegram_log');
            }
        }
    }

    /**
     * Если сообщение от клиента было прочитано пользователем RetailCRM
     * То необходимо пометить его как прочитанное, таким образом в RetailCRM можно отметить все исходящие сообщения как прочитанные клиентом
     * @param $retailMessage
     */
    private function setMessageReadInRetailCrm($retailMessage, $existSetting)
    {
        $body = [
            'Message' => [
                'external_id' => $retailMessage['data']['external_message_id']
            ],
            'channel_id' => $retailMessage['data']['channel_id']
        ];

        Yii::$app->transport->setMessageRead($existSetting, $body);

    }

    private function setChannelsToSetting($setting, $needChannelsToSave)
    {
        $setting->wazzup_channels = json_encode($needChannelsToSave);
        $setting->save();
    }
}
