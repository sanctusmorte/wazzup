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

class Logsis extends Component 
{

    public $host;

    public function init()
    {
        parent::init();
    
        if (empty($this->host)) throw new InvalidConfigException('No host specified');
    }

    /**
     * Выполнение запроса
     * 
     * @param string - $jwt
     * @param string - $method
     * @param string - $url
     * @param array - $body
     * @return array
     */

    public function makeRequest(string $method, string $url, array $body = []): array
    {
        $headers = [
            'Content-Type: application/json; charset=utf-8'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if (strtoupper($method) == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
        }

        if (!empty($body)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, Json::encode($body));
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        return [
            $http_code,
            Json::decode($response, true)
        ];
    }

    /**
     * Тестирование api-ключа
     * 
     * @param string
     * @return array
     */

    public function testApiKey(string $api_key)
    {
        $url = $this->host . '/testkey?key=' . $api_key;

        list($code, $response) = $this->makeRequest('GET', $url);

        return $response;
    }

}