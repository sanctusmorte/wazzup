<?php


namespace app\services;

use app\helpers\RetailTransportMgHelper;
use app\helpers\WazzupHelper;
use app\jobs\TemplateCreatelJob;
use app\jobs\TemplateUpdateJob;
use app\models\Setting;
use Yii;

class RetailTransportMgService
{
    private $settingService;
    private $retailTransportMgHelper;
    private $wazzupHelper;
    private $wazzupTemplates;

    public function __construct(SettingService $settingService, RetailTransportMgHelper $retailTransportMgHelper, WazzupHelper $wazzupHelper, WazzupTemplates $wazzupTemplates)
    {
        $this->settingService = $settingService;
        $this->retailTransportMgHelper = $retailTransportMgHelper;
        $this->wazzupHelper = $wazzupHelper;
        $this->wazzupTemplates = $wazzupTemplates;
    }

    public function createChannelsInRetailCrm($setting)
    {
        $needChannelsToSave = [];

        $channels = Yii::$app->wazzup->getChannels($setting);

        if ($channels !== false) {
            foreach ($channels as $channel) {
                if(isset($channel['state']) && $channel['state'] === 'blocked'){
                    continue;
                }
                if(in_array($channel['transport'], ['waba', 'wapi'])){
                    $channel['transport'] = 'whatsapp';
                }
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

    public function createTemplates($setting)
    {
        $existTemplates = $this->wazzupTemplates->getTemplatesByClientId($setting->client_id);

        if (count($existTemplates) > 0) {
            foreach ($existTemplates as $existTemplate) {
                try {
                    Yii::$app->transport->createTemplateInRetailCrm($setting, $existTemplate);
                } catch (\Exception $e) {
                    var_dump($e->getMessage());
                }

            }
        }
    }

    public function updateTemplates($setting)
    {
        $existTemplates = $this->wazzupTemplates->getTemplatesByClientId($setting->client_id);

        if (count($existTemplates) > 0) {
            foreach ($existTemplates as $existTemplate) {
                Yii::$app->transport->updateTemplateInRetailCrm($setting, $existTemplate);
            }
        }
    }

    /**
     * ???????????????????????? ?????????????????? ???? RetailCRM ?? ?????????????????? ?????? ("type") ??????????????????
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

                //Yii::error($retailMessage, 'wazzup_telegram_log');
                Yii::info($result, __METHOD__);
                //Yii::error($imageUrl, 'wazzup_telegram_log');
        }
    }

    /**
     * ???????????????????? ?????????????????? ???? RetailCRM ?? Wazzup
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

            Yii::info($response, __METHOD__);

            return $response;
        }
    }

    /**
     * ???????? ?????????????????? ???? ?????????????? ???????? ?????????????????? ?????????????????????????? RetailCRM
     * ???? ???????????????????? ???????????????? ?????? ?????? ??????????????????????, ?????????? ?????????????? ?? RetailCRM ?????????? ???????????????? ?????? ?????????????????? ?????????????????? ?????? ?????????????????????? ????????????????
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
