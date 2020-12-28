<?php
namespace app\jobs;

use Yii;

use app\helpers\LogsisHelper;

class TrackingJob extends \yii\base\BaseObject implements \yii\queue\Job
{
    public $_setting;

    public function execute($queue)
    {
        $statusData = LogsisHelper::generateStatusData($this->_setting);
        $response = Yii::$app->logsis->getstatusv3($statusData);

        if ($this->_setting->retail_api_url !== 'https://ava-med24.retailcrm.ru') {
            echo "<pre>"; print_r($statusData);
            echo "<pre>"; print_r($response);
            die;
        }

        $updateOrders = [];

        if ($response['status'] == 200) {
            
            foreach ($response['response'] ?? [] as $key => $orderStatus) {

                if ($prefix_shop = $this->_setting->prefix_shop) {
                    $number = str_replace($prefix_shop, '', $orderStatus['inner_id']);
                } else {
                    $number = $orderStatus['inner_id'];
                }

                $ordersListResponse = $this->getRetailOrders($number, $orderStatus['order_id']);

                if ($retailOrder = $ordersListResponse['orders'][0] ?? false) {
                    $updateOrders[$key] = $orderStatus;
                    $updateOrders[$key]['retail_order_id'] = $retailOrder['id'];
                    $updateOrders[$key]['retail_site'] = $retailOrder['site'];
                }
            }

            foreach ($updateOrders as $updateOrder) {
                if ($retailToLogsisStatus = $this->_setting->getRetailToLogsisStatusByLogsisStatusId($updateOrder['status'])) {

                    $orderEditResponse = $this->updateOrder($updateOrder['retail_order_id'], $retailToLogsisStatus->orderStatus->code, $updateOrder['retail_site']);
                }
            }   
        }
    }

    /**
     * Обновление заказа в retailCRm
     * 
     * @param int $order_id
     * @param string $order_status
     * @param string $site
     * @return object|boolean
     */

    private function updateOrder(int $order_id, string $order_status, string $site)
    {
        return Yii::$app->retail->ordersEdit($this->_setting->getRetailAuthData(), 'id', ['id' => $order_id, 'status' => $order_status], $site);
    }

    /**
     * Полуение заказа из retailCRM по номеру заказа и треку доставки
     * 
     * @param string $number
     * @param string $trackNumber
     * @return object|boolean
     */

    private function getRetailOrders(string $number, string $trackNumber)
    {
        return Yii::$app->retail->ordersList($this->_setting->getRetailAuthData(), ['numbers' => [$number]]);
    }
}