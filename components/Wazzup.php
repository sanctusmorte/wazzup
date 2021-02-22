<?php
namespace app\components;

use Yii;
use yii\base\Component;

use yii\helpers\{
    Url,
    Json
};

class Wazzup extends Component
{
    /**
     * @param $url
     * @param $apiKey
     * @return bool|string
     */
    private function makeGetRequest($url, $apiKey)
    {
        $headers = [
            'Authorization: Basic '.$apiKey.'',
            'Content-Type:application/json; charset=utf-8'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

    /**
     * @param $url
     * @param $apiKey
     * @param $body
     * @return bool|string
     */
    private function makePostRequest($url, $apiKey, $body)
    {
        $headers = [
            'Authorization: Basic '.$apiKey.'',
            'Content-Type:application/json'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    /**
     * @param $url
     * @param $apiKey
     * @param $body
     * @return bool|string
     */
    private function makePutRequest($url, $apiKey, $body)
    {
        $headers = [
            'Authorization: Basic '.$apiKey.'',
            'Content-Type:application/json'
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);


        return $response;
    }

    /**
     * @param $setting
     * @return bool|string
     */
    public function getChannels($setting)
    {
        $url = 'https://api.wazzup24.com/v2/channels';

        $response = $this->makeGetRequest($url, $setting->wazzup_api_key);

        if (isset($response['error'])) {
            return false;
        } else {
            return json_decode($response, 1);
        }
    }

    public function putUrlWebHook($setting)
    {
        $url = 'https://api.wazzup24.com/v2/webhooks';

        $body = [
            'url' => 'https://wazzup.imb-service.ru/wazzup/web-hook',
        ];

        $response = $this->makePutRequest($url, $setting->wazzup_api_key, $body);

        var_dump($response);
    }

    public function checkApiKey($apiKey)
    {
        $url = 'https://api.wazzup24.com/v2/channels';

        $response = json_decode($this->makeGetRequest($url, $apiKey), 1);

        if (isset($response['error'])) {
            return [
                'success' => false,
                'errorMsg' => $response['error']['code']
            ];
        } else {
            return [
                'success' => true,
            ];
        }
    }

    public function sentMessage($setting, $body)
    {
        $url = 'https://api.wazzup24.com/v2/send_message';

        $response = $this->makePostRequest($url, $setting['wazzup_api_key'], $body);

//        Yii::error($body, 'wazzup_telegram_log');
//        Yii::error($setting, 'wazzup_telegram_log');
//        Yii::error($response, 'wazzup_telegram_log');
    }
}
