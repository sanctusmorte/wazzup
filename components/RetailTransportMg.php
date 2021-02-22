<?php
namespace app\components;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\web\ServerErrorHttpException;

use yii\helpers\{
    Url,
    Json
};

class RetailTransportMg extends Component
{
    /**
     * @param string $url
     * @param $setting
     * @param array $body
     * @return bool|string
     */
    private function makePostRequest(string $url, string $mg_transport_token, array $body)
    {
        $headers = [
            'Content-Type: application/json; charset=utf-8',
            'X-Transport-Token: '.$mg_transport_token.''
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, Json::encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    public function createTransportInRetailCrm($setting, array $channel)
    {
        $url = $setting->mg_transport_endpoint_url . '/api/transport/v1/channels';

        $body = [
            'type' => $channel['transport'],
            'name' => $channel['plainId'],
            'external_id' => $channel['channelId'],
            'settings' => [
                'status' => [
                    'delivered' => 'both',
                    'read' => 'both'
                ],
                'text' => [
                    'creating' => 'both',
                    'editing' => 'both',
                ],
            ],
        ];

        $response =  $this->makePostRequest($url, $setting->mg_transport_token, $body);

        return json_decode($response, 1);
    }

    public function sentMessageToRetailCrm(array $data)
    {
        $url = $data['mg_transport_endpoint_url'] . '/api/transport/v1/messages';

        $response = $this->makePostRequest($url, $data['mg_transport_token'], $data['message']);

       // Yii::error($response, 'wazzup_telegram_log');
    }
}
