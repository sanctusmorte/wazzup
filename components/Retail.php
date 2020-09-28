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

class Retail extends Component 
{

    /**
     * Получение списка доступных методов и магазинов для данного ключа
     * @param array - $apiClient - ['retailApiUrl' => $url, 'retailApiKey' => $apiKey]
     * @return array
     */

    public function credentials($apiClient) 
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->credentials();
            
            if ($response->isSuccessful()) {
                
                return $response;
            } else {
                return false;
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            // Yii::error($e, $e->getMessage());
            // throw new ServerErrorHttpException($e->getMessage());
            return false;
        }
    }

    public function storesList(array $apiClient)
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->storesList();

            if ($response->isSuccessful()) {

                return $response;
            } else {
                return false;
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }


    /**
     * Получения списка магазинов
     * @param array - $apiClient - ['retailApiUrl' => $url, 'retailApiKey' => $apiKey]
     * @return array
     */

    public function sitesList($apiClient) 
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->sitesList();

            if ($response->isSuccessful()) {

                return $response['sites'];
            } else {
                return false;
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    /**
     * Редактирование настроек модуля
     * @param array - $apiClient - ['retailApiUrl' => $url, 'retailApiKey' => $apiKey]
     * @param array - $data - массив настроек модуля
     * @return boolean
     */

    public function moduleEdit($apiClient, $data)
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->integrationModulesEdit($data);
            echo "<pre>"; print_r($response); die;
            if ($response->isSuccessful()) { 
                return true;
            } else {
                return false;
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    /**
     * Получения платежных статусов 
     * @param array - $apiClient - ['retailApiUrl' => $url, 'retailApiKey' => $apiKey]
     * @return array
     */

    public function paymentStatusesList($apiClient)
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->paymentStatusesList();

            if ($response->isSuccessful()) { 
                return $response['paymentStatuses'] ?? false;
            } else {
                return false;
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    /**
     * Получение списка типов доставки
     * @param array - $apiClient - ['retailApiUrl' => $url, 'retailApiKey' => $apiKey]
     * @return array
     */

    public function deliveryTypesList($apiClient) 
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->deliveryTypesList();

            if ($response->isSuccessful()) {
                return $response['deliveryTypes'] ?? false;
            } else {
                return false;
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    /**
     * Получение списка типов оплаты
     * @param array - $apiClient - ['retailApiUrl' => $url, 'retailApiKey' => $apiKey]
     * @return array
     */

    public function paymentTypesList($apiClient) 
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->paymentTypesList();

            if ($response->isSuccessful()) {
                return $response['paymentTypes'] ?? false;
            } else {
                return false;
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    /**
     * Получение списка курьеров
     * @param array - $apiClient - ['retailApiUrl' => $url, 'retailApiKey' => $apiKey]
     * @return array
     */

    public function couriersList($apiClient) 
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->couriersList();

            if ($response->isSuccessful()) {
                return $response['couriers'] ?? [];
            } else {
                return false;
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }


    /**
     * Метод позволяет изменить данные инвойса в системе
     * @param array - $apiClient - ['retailApiUrl' => $url, 'retailApiKey' => $apiKey]
     * @param array - data - массив с данными инвойса
     */

    public function paymentUpdateInvoice($apiClient, $data) 
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->paymentUpdateInvoice($data);

            if ($response->isSuccessful()) { 
               return [
                   'status' => true
               ];
            } else {
                return [
                    'status' => false,
                    'error' => $response->getErrorMsg()
                ];
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    /**
     * получения настроек модуля
     * @param array $apiClient - ['retailApiUrl' => $url, 'retailApiKey' => $apiKey]
     * @param string code
     * @return boolean
     */

    public function integrationModulesGet($apiClient, $code)
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->integrationModulesGet($code);

            if ($response->isSuccessful()) { 
               return $response;
            } else {
                return [
                    'status' => false,
                    'error' => $response->getErrorMsg()
                ];
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    public function paymentCheckInvoice($apiClient, $check) 
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->paymentCheckInvoice($check);

            if ($response->isSuccessful()) { 
               return [
                   'status' => true
               ];
            } else {
                throw new ServerErrorHttpException($e->getMessage());
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    /**
     * Получения статусов заказа
     * @param array $apiClient - ['retailApiUrl' => $url, 'retailApiKey' => $apiKey]
     * @return array 
     */

    public function statusesList($apiClient) 
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->statusesList();

            if ($response->isSuccessful()) { 
                return $response['statuses'];
            } else {
                throw new ServerErrorHttpException($response->getErrorMsg());
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    /**
     * Создания заказа
     * @param $apiClient - ['retailApiUrl' => $url, 'retailApiKey' => $apiKey]
     * @param $data - массив с информацией о заказе
     * @return boolean
     */

    public function ordersCreate($apiClient, array $data, $site = null) 
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->ordersCreate($data, $site);

            if ($response->isSuccessful()) { 
                return $response;
            } else {

                Yii::error([ 
                    'error' => 'Ошибка создания заказа',
                    'number' => $data['number']
                ], "Ошибка создания заказа");

                return $response;
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, "Номер заказа: " . $data['number'] . ". " . $e->getMessage());
            // throw new ServerErrorHttpException($e->getMessage());

            return false;
        }
    }

    public function ordersPaymentCreate($apiClient, array $data, $site = null)
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->ordersPaymentCreate($data, $site);

            if ($response->isSuccessful()) { 
                return $response;
            } else {
                return $response;
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());

            return false;
        }
    }

    public function ordersPaymentEdit($apiClient, array $data, $by = 'id', $site = null)
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->ordersPaymentEdit($data, $by, $site);
            if ($response->isSuccessful()) { 
                return $response;
            } else {
                return false;
            }

        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());

            return false;
        }
    }



    /**
     * Создание пользовательского поля
     * @param array $apiClient - ['retailApiUrl' => $url, 'retailApiKey' => $apiKey]
     * @param string - $entity
     * @param array - $data
     */

    public function customFieldsCreate($apiClient, $entity, array $data)
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->customFieldsCreate($entity, $data);
            
            if ($response->isSuccessful()) { 
                return true;
            } else {
                return false;
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());

            return false;
        }
    }

    /**
     * Получение пользовательского поля по коду
     * @param array $apiClient - ['retailApiUrl' => $url, 'retailApiKey' => $apiKey]
     * @param string - $entity
     * @param string - $code
     */

    public function customFieldsGet(array $apiClient, string $entity, string $code)
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{

            $response = $client->request->customFieldsGet($entity, $code);
            if ($response->isSuccessful()) { 
                return $response;
            } else {
                return false;
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());

            return false;
        }
    }

    /**
     * Получение списка товаров с торговыми предложениями, удовлетворяющих заданному фильтру
     * @param array $apiClient - ['retailApiUrl' => $url, 'retailApiKey' => $apiKey]
     * @param  array $filter - фильтр для поиска товаров
     */

    public function storeProducts($apiClient, array $filter)
    {
        
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->storeProducts($filter, $page = null, $limit = null);

            if ($response->isSuccessful()) { 
                return $response['products'];
            } else {
                return false;
            }

        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, "apiUrl: " . $apiClient['retailApiUrl'] . ". " . $e->getMessage());
            
            return false;
        }
    }
    
    /**
     * Получение списка клиентов, удовлетворяющих заданному фильтру
     * @param $apiClient - ['retailApiUrl' => $url, 'retailApiKey' => $apiKey]
     * @param $filter - фильтр для поиска клиентов
     */

    public function customersList($apiClient, array $filter, $page = null, $limit = null) {
        
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->customersList($filter, $page, $limit);

            if ($response->isSuccessful()) { 
                return $response['customers'];
            } else {
                return false;
            }

        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, "apiUrl: " . $apiClient['retailApiUrl'] . ". " . $e->getMessage());
            
            return false;
        }


    }

    public function ordersHistory($apiClient, array $filter, $page = null, $limit = null) {
        
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->ordersHistory($filter, $page = null, $limit = null);

            if ($response->isSuccessful()) { 
                
                return [
                    $response['history'] ?? false,
                    $response['pagination'] ?? false
                ];

            } else {
                return [
                    false,
                    false
                ];
            }

        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, "apiUrl: " . $apiClient['retailApiUrl'] . ". " . $e->getMessage());
            
            return [
                false,
                false
            ];
        }
    }

    public function ordersStatuses($apiClient, array $ids = [], array $externalIds = []) {

        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->ordersStatuses($ids, $externalIds);

            echo "<pre>"; print_r($response); die;

        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    public function ordersGet($apiClient, $id, $by = 'externalId', $site = null) {

        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->ordersGet($id, $by, $site);

            if ($response->isSuccessful()) { 
                return $response['order'] ?? [];
            } else {
                return [];
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, "apiUrl: " . $apiClient['retailApiUrl'] . ". " . $e->getMessage());

            return [];
            // throw new ServerErrorHttpException($e->getMessage());
        }
    }

    public function ordersEdit($apiClient, $by = 'externalId', $data, $site = null)
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->ordersEdit($data, $by, $site);

            if ($response->isSuccessful()) {
                return $response;
            } else {
                return false;
            }
        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    public function ordersList($apiClient, array $filter = [], $page = null, $limit = null) 
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->ordersList($filter, $page, $limit);

            if ($response->isSuccessful()) {
                return $response;
            } else {
                return false;
            }

        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    public function usersList($apiClient, array $filter = [], $page = null, $limit = null)
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->usersList($filter, $page, $limit);

            if ($response->isSuccessful()) {
                return $response;
            } else {
                return false;
            }

        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    public function telephonyCallEvent(array $apiClient, array $data)
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->telephonyCallEvent(
                $data['phone'],
                $data['type'],
                $data['codes'],
                $data['hangupStatus'] ?? null,
                $data['externalPhone'] ?? null,
                $data['webAnalyticsData'] ?? [],
                $data['site']
            );

            if ($response->isSuccessful()) {
                return $response;
            } else {
                return false;
            }

        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

    public function telephonyCallsUpload(array $apiClient, array $calls)
    {
        $client = new \RetailCrm\ApiClient(
            $apiClient['retailApiUrl'],
            $apiClient['retailApiKey'],
            \RetailCrm\ApiClient::V5
        );

        try{
            $response = $client->request->telephonyCallsUpload($calls);

            if ($response->isSuccessful()) {
                return $response;
            } else {
                return false;
            }

        } catch (\RetailCrm\Exception\CurlException $e) {
            Yii::error($e, $e->getMessage());
            throw new ServerErrorHttpException($e->getMessage());
        }
    }

}