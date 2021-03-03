<?php
namespace app\services;

use Yii;
use yii\base\Component;
use yii\helpers\{
    Url,
    Json
};
use app\models\{
    Setting,
};

class SettingService extends Component
{
    /**
     * @return array|mixed
     */
    public function getSettingId()
    {
        $clientId = Yii::$app->request->post('clientId');
        if ($clientId === null) {
            $clientId = Yii::$app->session->get('clientId');
        }
        return $clientId;
    }

    /**
     * @param string $clientId
     * @return Setting|array|\yii\db\ActiveRecord|null
     */
    public function getSetting($clientId = '')
    {
        if ($setting = Setting::find()->where(['client_id' => $clientId])->one()) return $setting;

        return new Setting([
            'client_id' => $this->generateClientId()
        ]);
    }

    /**
     * @param int $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getSettingById($id)
    {
        if ($setting = Setting::find()->where(['client_id' => $id])->one()) {
            return $setting;
        } else {
            return null;
        }
    }

    /**
     * @param $setting
     * @return bool
     */
    public function save($setting): bool
    {
        $moduleEdit = $this->moduleEdit($setting);
        if ($moduleEdit['success'] === false) {
            //Yii::error($moduleEdit['logMsg'], 'wazzup_telegram_log');
        } else {
            $setting = $moduleEdit['setting'];
        }
        $setting->save();
        Yii::$app->getSession()->setFlash("success", "Настройки модуля сохранены.");
        return true;
    }

    /**
     * @param Setting $setting
     * @return array
     */
    private function moduleEdit(Setting $setting)
    {
        $moduleData = [
            'integrationCode' => 'wazzup-transport',
            'code' => 'wazzup-transport',
            'clientId' => $setting->client_id,
            'baseUrl' => 'https://wazzup.imb-service.ru',
            'accountUrl' => 'https://wazzup.imb-service.ru/setting',
            'active' => true,
            'freeze' => false,
            'name' => 'Wazzup',
            'actions' => [
                'activity' => '/setting/activity'
            ],
            'integrations' => [
                'mgTransport' => [
                    "webhookUrl" => 'https://wazzup.imb-service.ru/retail/web-hook?uuid='.$setting->retail_crm_web_hook_uuid.''
                ]
            ],
        ];

        $moduleEdit = Yii::$app->retail->moduleEdit($this->getRetailAuthData($setting), $moduleData);

        if ($moduleEdit['success'] === false) {
            return $moduleEdit;
        } else {
            $moduleEditResponse = $moduleEdit['data'];
            if (isset($moduleEditResponse['info']['mgTransport']['endpointUrl'])) {
                $setting->mg_transport_endpoint_url = $moduleEditResponse['info']['mgTransport']['endpointUrl'];
            }
            if (isset($moduleEditResponse['info']['mgTransport']['endpointUrl'])) {
                $setting->mg_transport_token = $moduleEditResponse['info']['mgTransport']['token'];
            }
            $setting->save();

            return [
                'success' => true,
                'setting' => $setting
            ];
        }
    }


    /**
     * @param Setting $setting
     * @return array
     */
    private function getRetailAuthData(Setting $setting): array
    {
        return [
            'retailApiUrl' => $setting->retail_api_url,
            'retailApiKey' => $setting->retail_api_key
        ];
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateClientId(): string
    {
        while (true) {
            $clientId = Yii::$app->security->generateRandomString(32);

            if (!Setting::find()->where(['client_id' => $clientId])->one()) {
                return $clientId;
            }
        }
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateRetailCrmWebHookUuid(): string
    {
        while (true) {
            $uuid = Yii::$app->security->generateRandomString(32);

            if (!Setting::find()->where(['retail_crm_web_hook_uuid' => $uuid])->one()) {
                return $uuid;
            }
        }
    }

    /**
     * @return string
     * @throws \yii\base\Exception
     */
    public function generateWazzupWebHookUuid(): string
    {
        while (true) {
            $uuid = Yii::$app->security->generateRandomString(32);

            if (!Setting::find()->where(['wazzup_web_hook_uuid' => $uuid])->one()) {
                return $uuid;
            }
        }
    }


    /**
     * Возвращает информацию о канале
     * Поиск осуществляется по всем настройкам клиентов из БД из поля wazzup_channels
     * Данные о сообщении, которое приходит из Wazzup содержит в себе externalId канала (пример - e6d12d13-87f6-4c21-bf40-bc037d99b5a6)
     * По нему нам необходимо получить channelId, mg_transport_token, mg_transport_endpoint_url клиента
     * @param $channelId
     * @param $existSetting
     * @return int|null
     */
    public function getChannelDataByChannelId($channelId, $existSetting)
    {
        $needChannelId = null;

        $channels = json_decode($existSetting->wazzup_channels, 1);
        foreach ($channels as $channel) {
            if ($channel['external_id'] === $channelId) {
                $needChannelId = $channel['id'];
                break;
            }
        }

        return $needChannelId;
    }

    /**
     * Возвращает информацию о канале
     * Поиск осуществляется по всем настройкам клиентов из БД из поля wazzup_channels
     * Данные о сообщении, которое приходит из RetailCRM содержит в себе channel_id канала (пример - 12)
     * По нему нам необходимо получить ID (пример - 12)
     * @param $channelId
     * @param $existSetting
     * @return array
     */
    public function getChannelInfoByChannelIdFromRetailCrm($channelId, $existSetting): array
    {
        $data = null;
        $needChannelExternalId = null;
        $needChannelType = null;



        $existChannels = json_decode($existSetting->wazzup_channels, 1);



        if (count($existChannels) > 0) {
            foreach ($existChannels as $existChannel) {
                if ($existChannel['id'] === $channelId) {
                    $needChannelExternalId = $existChannel['external_id'];
                    $needChannelType = $existChannel['channelType'];
                    break;
                }
            }
        }

        if ($needChannelExternalId !== null and $needChannelType !== null) {
            $data = [
                'channelId' => $needChannelExternalId,
                'chatType' => $needChannelType,
            ];
        }

        Yii::error($data, 'wazzup_telegram_log');

        return $data;
    }

    public function setChannelsList($channels, $existSetting)
    {
        $needChannels = [];
        foreach (json_decode($channels['channelsList'], 1) as $channel) {
            $needChannels[] = [
                'external_id' => $channel['channelId'],
                'channel_type' => $channel['transport'],
                'id' => $channel['']
            ];
        }

        //Yii::error($needChannels, 'wazzup_telegram_log');

        $existSetting->wazzup_channels = json_encode($needChannels);
    }

}
