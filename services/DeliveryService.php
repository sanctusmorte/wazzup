<?php 
namespace app\services;

use Yii;
use yii\base\Component;
use yii\helpers\{
    Url,
    Json
};
use yii\web\{
    NotFoundHttpException,
    ServerErrorHttpException,
    BadRequestHttpException
};

use app\models\{
    Setting,
    Shop, 
    OrderStatus,
    SettingShop,
    RetailToLogsisStatus
};
use app\helpers\LogsisHelper;

class DeliveryService extends Component 
{

    /**
     * Расчет стоимости товаров, выбор тарифов
     * 
     * @return array
     */

    public function calculate(): array
    {
        $setting = $this->getSetting(Yii::$app->request->post('clientId'));
        $calculate = Json::decode(Yii::$app->request->post('calculate'));

        $access = $this->accesssCheck($setting);
        if ($access['success'] == false) return $access;

        $shipmentAddressAccess = $this->checkShipmentAddress($calculate);
        if ($shipmentAddressAccess['success'] == false) return $shipmentAddressAccess;

        $calculateData = LogsisHelper::generateCalculateData($setting, $calculate);
        
        $response = Yii::$app->logsis->calculate($calculateData);

        if ($response['status'] == 400) {
            return [
                'success' => false,
                'errorMsg' => 'Ошибка при обращении к калькулятору.'
            ];  
        } elseif ($response['status'] == 402) {
            return [
                'success' => false,
                'errorMsg' => $this->changeMessage($response['response']['message'])
            ];
        } elseif ($response['status'] == 200) {
            return [
                'success' => true,
                'result' => [
                    [
                        'code' => 1,
                        'name' => 'Доставка Logsis',
                        'type' => 'courier',
                        'cost' => $response['response']['total']
                    ]
                ]
            ];
        } else {
            return [
                'success' => false,
                'errorMsg' => 'Получен некорректный ответ от Logsis. Повторите попытку.'
            ];
        }
    }

    /**
     * Передача заказа в Logsis
     * 
     * @return array
     */

    public function save(): array
    {
        $setting = $this->getSetting(Yii::$app->request->post('clientId'));
        $save = Json::decode(Yii::$app->request->post('save'));

        $access = $this->accesssCheck($setting);
        if ($access['success'] == false) return $access;

        $accessShop = $this->accessShop($save['site'], $setting);
        if ($accessShop['success'] == false) return $accessShop;

        $saveData = LogsisHelper::generateSaveData($setting, $save);

        $responseCreateOrder = Yii::$app->logsis->createorder($saveData);
        
        if ($responseCreateOrder['status'] !== '200') {
            return [
                'success' => false,
                'errorMsg' => $responseCreateOrder['response']['Error']
            ];
        } 
        
        $confirmData =  LogsisHelper::generateConfirmOrderData($setting, $responseCreateOrder['response']);
        $responseConfirmOrder = Yii::$app->logsis->confirmorder($confirmData);

        if ($responseConfirmOrder['status'] !== '200') {
            return [
                'success' => false,
                'errorMsg' => $responseConfirmOrder['response']['Error']
            ];
        } else {
            return [
                'success' => true,
                'result' => [
                    'deliveryId' => $responseConfirmOrder['response']['order_id'],
                    'trackNumber' => $responseCreateOrder['response']['inner_track'],
                    'cost' => $responseCreateOrder['response']['price_delivery'],
                    'status' => 2
                ]
            ];
        }
    }

    /**
     * Проверка магазина на доступность модуля
     * 
     * @param string $code
     * @param object $setting
     * @return array
     */

    private function accessShop(string $code, Setting $setting): array
    {
        foreach ($setting->settingShops as $shop) {
            if ($code == $shop->code) {
                return [
                    'success' => true
                ];
            }
        }

        return [
            'success' => false,
            'errorMsg' => 'Для данного магазина модуль не доступен.'
        ];
    }

    /**
     * Замена сообщения 
     * 
     * @param string $message
     * @return string
     */

    private function changeMessage(string $message): string 
    {
        if (strpos($message, 'Не найден тариф для кладра') !== false) {
            return 'Служба доставки не доставляет по указанному адресу.';
        }

        return $message;
    }

    /**
     * Проверка адреса отправления
     * 
     * @param array $calculate
     * @return array
     */

    private function checkShipmentAddress(array $calculate): array
    {
        if (!in_array($calculate['shipmentAddress']['region'], ['Москва город', 'Московская область'])) {
            return [
                'success' => false,
                'errorMsg' => 'Тарифы не найдены. Проверьте склад отправителя. Доставка Logsis возможна только из Москвы.'
            ];
        }
            
        return ['success' => true];
    }

    /**
     * Проверка на доступ модуля
     * 
     * @param object
     * @return array
     */

    private function accesssCheck(Setting $setting): array
    {
        if ($setting->is_active == Setting::STATUS_DISABLE) {
            return [
                'success' => false,
                'errorMsg' => 'Модуль деактивирован.'
            ];
        } elseif ($setting->is_freeze == Setting::STATUS_FREEZE) {
            return [
                'success' => false,
                'errorsMsg' => 'Модуль заморожен.'
            ];
        } else {
            return [
                'success' => true
            ];
        }
    }

   /**
     * Получение настроек модуля
     * 
     * @param string
     * @return object
     */

    private function getSetting(string $clientId): Setting
    {
        if ($setting = Setting::find()->where(['client_id' => $clientId])->one()) return $setting;

        throw new NotFoundHttpException("Setting #$clientId not found");
    }

}