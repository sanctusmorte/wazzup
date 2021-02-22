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
            'name' => 'Wazzup чаты v1.4 [dev-max]',
            'actions' => [
                'activity' => '/setting/activity'
            ],
            'integrations' => [
                'mgTransport' => [
                    "webhookUrl" => "https://wazzup.imb-service.ru/retail/web-hook"
                ]
            ],
        ];

        $moduleEdit = Yii::$app->retail->moduleEdit($this->getRetailAuthData($setting), $moduleData);

        var_dump($moduleEdit);

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
     * Возвращает ID канала RetailCRM
     * Поиск осуществляется по всем настройкам клиентов из БД из поля wazzup_channels
     * Данные о сообщении, которое приходит из Wazzup содержит в себе externalId канала (пример - e6d12d13-87f6-4c21-bf40-bc037d99b5a6)
     * По нему нам необходимо получить ID (пример - 12)
     * @param $channelId
     * @return array
     */
    public function getChannelIdByChannelIdFromWazzup($channelId): array
    {
        $data = null;
        $needChannelId = null;
        $setting = Setting::find()->where(['like', 'wazzup_channels', '%' . $channelId . '%', false])->one();

        Yii::error($setting->mg_transport_endpoint_url, 'wazzup_telegram_log');

        if ($setting !== null) {
            $channels = json_decode($setting->wazzup_channels, 1);
            foreach ($channels as $channel) {
                if ($channels['external_id'] === $channelId) {
                    $needChannelId = $channel['id'];
                    break;
                }
            }

            if ($needChannelId !== null) {
                $data = [
                    'channelId' => $needChannelId,
                    'mg_transport_token' => $setting->mg_transport_token,
                    'mg_transport_endpoint_url' => $setting->mg_transport_endpoint_url
                ];
            }
        }

        return $data;
    }

}
