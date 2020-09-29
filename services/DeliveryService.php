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
                'errorMsg' => 'Ошибка при обращении к калькулятору'
            ];  
        } elseif ($response['status'] == 402) {
            return [
                'success' => false,
                'errorMsg' => $response['response']['message']
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