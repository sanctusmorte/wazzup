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

    private function makeRequest(string $method, string $url, array $body = []): array
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
     * Отправка POST запроста
     * 
     * @param string $url
     * @param array $body
     * @return array
     */

    private function makeRequestPost(string $url, array $body = []): array
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($body),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/x-www-form-urlencoded"
            ],
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        Yii::info($url . ' : ' . print_r($response, true));

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

    /**
     * Расчет стоимости доставки
     * 
     * @param array $data 
     * @return array
     */

    public function calculate(array $data): array
    {
        $url = 'http://api.logsis.ru/api/v1/public/calculate';

        list($code, $response) = $this->makeRequest('POST', $url, $data);

        return $response;
    }



    /**
     * Создание заказа 
     * 
     * @param array $data
     * @return array
     */

    public function createorder(array $data): array
    {
        $url = $this->host . '/createorder';

        list($code, $response) = $this->makeRequestPost($url, $data);   

        return $response;
    }

    /**
     * Подтверждение заказа
     * 
     * @param array $data
     * @return array
     */

    public function confirmorder(array $data): array
    {
        $url = $this->host . '/confirmorder';

        list($code, $response) = $this->makeRequestPost($url, $data);   

        return $response;
    }

    /**
     * Массовое получение статусов
     * 
     * @param array $data
     * @return array
     */

    public function getstatusv3(array $data): array
    {
        $url = $this->host . '/getstatusv3';

        $url .= '?' . http_build_query(['key' => $data['key']]);

        $url .= '&' . http_build_query(['from' => $data['from']]);

        $url .= '&' . http_build_query(['to' => $data['to']]);

        list($code, $response) = $this->makeRequestPost($url, $data);   

        return $response;
    }
}