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
     * @param string $mg_transport_token
     * @param array $body
     * @return bool|string
     */
    private function makePostRequest(string $url, string $mg_transport_token, array $body)
    {
        $headers = [
            'Content-Type: application/text; charset=utf-8',
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

    /**
     * @param string $url
     * @param string $mg_transport_token
     * @param array $body
     * @return bool|string
     */
    private function makePutRequest(string $url, string $mg_transport_token, array $body)
    {
        $headers = [
            'Content-Type: application/json; charset=utf-8',
            'X-Transport-Token: '.$mg_transport_token.''
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_PUT, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, Json::encode($body));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }

    /**
     * @param $url
     * @param $apiKey
     * @return bool|string
     */
    private function makeGetRequest(string $url, string $mg_transport_token)
    {
        $headers = [
            'Content-Type: application/json; charset=utf-8',
            'X-Transport-Token: '.$mg_transport_token.''
        ];


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
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
                    'Delivered' => 'both',
                    'Read' => 'both'
                ],
                'text' => [
                    'Creating' => 'both',
                    'Editing' => 'both',
                    'Quoting' => 'both',
                    'Deleting' => 'both',
                    'max_chars_count' => 4096
                ],
                'image' => [
                    'Creating' => 'both',
                    'Editing' => 'both',
                    'Quoting' => 'both',
                    'Deleting' => 'both',
                    'max_items_count' => 4096
                ],
                'file' => [
                    'Creating' => 'both',
                    'Editing' => 'both',
                    'Quoting' => 'both',
                    'Deleting' => 'both',
                    'max_items_count' => 1
                ],
                'suggestions' => [
                    'Text' => 'both',
                    'Email' => 'both',
                    'Phone' => 'both',
                ],
                'sending_policy' => [
                    'new_customer' => 'template',
                    'after_reply_timeout' => 'template'
                ],
            ],
        ];

        if ($body['type'] === 'whatsapp') {
            $body['settings']['customer_external_id'] = 'phone';
        }

        $response =  $this->makePostRequest($url, $setting->mg_transport_token, $body);

        return json_decode($response, 1);
    }

    public function createTemplateInRetailCrm($setting, array $template)
    {
        $url = $setting->mg_transport_endpoint_url . '/api/transport/v1/channels/'.$template['channelId'].'/templates';

        $response =  $this->makePostRequest($url, $setting->mg_transport_token, $template['templateInfo']);

        //Yii::error([$template, $response], 'wazzup_telegram_log');
    }

    public function updateTemplateInRetailCrm($setting, array $template)
    {
        $url = $setting->mg_transport_endpoint_url . '/api/transport/v1/channels/'.$template['channelId'].'/templates/'.$template['templateInfo']['Code'].'';


        $response = $this->makePutRequest($url, $setting->mg_transport_token, $template['templateInfo']);


//        Yii::error([$template, $response], 'wazzup_telegram_log');
    }



    public function sentMessageToRetailCrm($existSetting, $body)
    {
        $url = $existSetting->mg_transport_endpoint_url . '/api/transport/v1/messages';

        $response = $this->makePostRequest($url, $existSetting->mg_transport_token, $body);

        Yii::error($response, 'wazzup_telegram_log');


        if ($existSetting->wazzup_web_hook_uuid === "6bKBm96yQqmQR3BlriTupzHXiSIHcjVU") {
            Yii::error([$body, $response], 'wazzup_telegram_log');
            //Yii::error($result, 'wazzup_telegram_log');
            //Yii::error($imageUrl, 'wazzup_telegram_log');
        }



    }

    public function uploadFileByUrl($existSetting, $request)
    {
        $url = $existSetting->mg_transport_endpoint_url . '/api/transport/v1/files/upload_by_url';
        $response = $this->makePostRequest($url, $existSetting->mg_transport_token, $request);
        return $response;
    }

    public function setMessageRead($existSetting, array $body)
    {
        $url = $existSetting->mg_transport_endpoint_url . '/api/transport/v1/messages/read';
        $response = $this->makePostRequest($url, $existSetting->mg_transport_token, $body);
        //Yii::error($response, 'wazzup_telegram_log');
    }

    public function getFileUrl($existSetting, $fileId)
    {
        $url = $existSetting->mg_transport_endpoint_url . '/api/transport/v1/files/' . $fileId;
        $response =  json_decode($this->makeGetRequest($url, $existSetting->mg_transport_token), 1);
        if (isset($response['Url'])) {
            return $response['Url'];
        } else {
            return false;
        }
    }
}

