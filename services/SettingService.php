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
    RetailToLogsisStatus,
    PaymentType,
    PaymentTypeSetting
};

class SettingService extends Component 
{

    /**
     * Получение настроек
     * 
     * @param string
     * @return object
     */

    public function getSetting(string $clientId = ''): Setting
    {
        if ($setting = Setting::find()->where(['client_id' => $clientId])->one()) return $setting;

        return new Setting([
            'client_id' => $this->generateClientId()
        ]);
    }
 
    /**
     * Получение настроек по идентификатору
     * 
     * @param integer
     * @return object
     */

    public function getSettingById(int $id): Setting
    {
        if ($setting = Setting::find()->where(['id' => $id])->one()) return $setting;

        throw new NotFoundHttpException("Setting #$id not found");
    }

    /**
     * Сохранение настроек
     * 
     * @param object $setting
     * @return boolean
     */

    public function save(Setting $setting): bool
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($setting->isNewRecord) {
                $setting->is_active = $setting::STATUS_DISABLE;
                $setting->is_freeze = $setting::STATUS_UNFREEZE;
            }

            $setting->save();

            if ($settingShops = $setting->settingShops) {
                foreach ($settingShops as $settingShop) {
                    $setting->unlink('settingShop', $settingShop, true);
                }
            }

            if ($setting->shop_ids) {
                foreach ($setting->shop_ids as $shop_id) {
                    $settingShop = new SettingShop([
                        'setting_id' => $setting->id,
                        'shop_id' => $shop_id
                    ]);
                    
                    if ($settingShop->validate()) $settingShop->save();
                }
            }

            if ($orderStatuses = $setting->order_statuses) $this->synchStatusSave($setting, $orderStatuses);
            if ($setting->payment_types && $setting->payment_types_cod) $this->synchPaymentTypeSave($setting);

            $this->getShops($setting);
            $this->getOrderStatus($setting);
            $this->getPaymentType($setting);
            $this->moduleEdit($setting);

            $transaction->commit();

            Yii::$app->session->set('clientId', $setting->client_id);
            Yii::$app->getSession()->setFlash("success", "Настройки модуля сохранены.");

            return true;

        } catch(\Exception $th) {
            $transaction->rollBack();

            Yii::error($th->getMessage(), 'Ошибка сохранения настроек модуля.');
            Yii::$app->getSession()->setFlash("error", "Ошибка сохранения настроек модуля.");

            return false;
        }
    }
    
    /**
     * Фоновое обновление настроек
     * 
     * @param object $setting
     * @return boolean
     */

    public function backgroundUpdateSetting(Setting $setting): bool
    {
        $this->getShops($setting);
        $this->getOrderStatus($setting);
        $this->getPaymentType($setting);

        return true;
    }

    /**
     * Получение магазинов из retailCRM
     * 
     * @param object $setting
     * @return boolean
     */

    private function getShops(Setting $setting): bool
    {
        if ($retailShops = Yii::$app->retail->sitesList($this->getRetailAuthData($setting))) {
            foreach ($retailShops as $retailShop) {
                
                $shop = Shop::find()->where(['setting_id' => $setting->id])->andWhere(['code' => $retailShop['code']])->one();

                if ($shop) {
                    $shop->name = $retailShop['name'];
                    $shop->code = $retailShop['code'];
                    $shop->url = $retailShop['url'];
                } else {
                    $shop = new Shop();

                    $shop->setting_id = $setting->id;
                    $shop->name = $retailShop['name'];
                    $shop->code = $retailShop['code'];
                    $shop->url = $retailShop['url'];
                }

                if ($shop->validate()) $shop->save();
            }
        }
        return true;
    }

    /**
     * Получение типов оплат
     * 
     * @param object $setting
     * @return boolean
     */

    private function getPaymentType(Setting $setting): bool
    {
        if ($retailPaymentTypes = Yii::$app->retail->paymentTypesList($this->getRetailAuthData($setting))) {
            foreach ($retailPaymentTypes as $retailPaymentType) {
                $paymentType = PaymentType::find()->where(['setting_id' => $setting->id])->andWhere(['code' => $retailPaymentType['code']])->one();

                if ($paymentType && $retailPaymentType['active']) {
                    $paymentType->name = $retailPaymentType['name'];

                    if ($paymentType->validate()) $paymentType->save();
                } elseif ($paymentType && !$retailPaymentType['active']) {
                    $paymentType->delete();
                } elseif ($retailPaymentType['active']) {
                    $paymentType = new PaymentType([
                        'setting_id' => $setting->id,
                        'name' => $retailPaymentType['name'],
                        'code' => $retailPaymentType['code']
                    ]);

                    if ($paymentType->validate()) $paymentType->save();
                }
            }
        }

        return true;
    }

    /**
     * Сохранение статусов сопоставления
     * 
     * @param object $setting
     * @param array $order_statuses
     * @return boolean
     */

    private function synchStatusSave(Setting $setting, array $orderStatuses): bool
    {
        foreach ($orderStatuses as $key => $orderStatus) {

            $retailToLogsisStatus = RetailToLogsisStatus::find()->where(['setting_id' => $setting->id])->andWhere(['logsis_status_id' => $key])->one();

            if ($retailToLogsisStatus && $orderStatus) {
                $retailToLogsisStatus->order_status_id = $orderStatus;
                $retailToLogsisStatus->logsis_status_id = $key;

                if ($retailToLogsisStatus->validate()) $retailToLogsisStatus->save();
            } elseif($retailToLogsisStatus && !$orderStatus) {
                
                $retailToLogsisStatus->delete();
            } elseif ($orderStatus) {
                $retailToLogsisStatus = new RetailToLogsisStatus([
                    'setting_id' => $setting->id,
                    'order_status_id' => $orderStatus,
                    'logsis_status_id' => $key
                ]);

                if ($retailToLogsisStatus->validate()) $retailToLogsisStatus->save();
            }
        }
        return true;
    }

    /**
     * Сохранение сопоставления типов оплат
     * 
     * @param object $setting
     * @return boolean
     */

    private function synchPaymentTypeSave(Setting $setting): bool
    {
        foreach ($setting->payment_types as $key => $payment_type) 
        {
            $paymentTypeSetting = PaymentTypeSetting::find()->where(['setting_id' => $setting->id])->andWhere(['payment_type_id' => $key])->one();

            if ($paymentTypeSetting && $payment_type) {
                $paymentTypeSetting->active = $payment_type;
                $paymentTypeSetting->cod = $setting->payment_types_cod[$key] ?? 0;

                if ($paymentTypeSetting->validate()) $paymentTypeSetting->save();
            } elseif ($paymentTypeSetting && !$payment_type) {

                $paymentTypeSetting->delete();
            } elseif ($payment_type) {
                $paymentTypeSetting = new PaymentTypeSetting([
                    'setting_id' => $setting->id,
                    'payment_type_id' => $key,
                    'active' => $payment_type,
                    'cod' => $setting->payment_types_cod[$key] ?? 0
                ]);

                if ($paymentTypeSetting->validate()) $paymentTypeSetting->save();
            }
        }

        return true;
    }

    /**
     * Обновление настроек модуля
     * 
     * @param object
     * @return boolean
     */

    private function moduleEdit(Setting $setting): bool
    {
        $moduleData = [
            'integrationCode' => 'logsis',
            'code' => 'logsis',
            'clientId' => $setting->client_id,
            'baseUrl' => 'https://retailcrm.logsis.ru/',
            'accountUrl' => 'https://retailcrm.logsis.ru/setting',
            'active' => ($setting->is_active == 1) ? true : false,
            'freeze' => ($setting->is_freeze == 1) ? true : false,
            'name' => 'Logsis DEV',
            'actions' => [
                'activity' => '/setting/activity'
            ],
            'integrations' => [
                'delivery' => [
                    'actions' => [
                        'calculate' => '/delivery/calculate',
                        'save' => '/delivery/save',
                        'get' => '/delivery/get-info',
                        'delete' => '/delivery/delete',
                        'shipmentSave' => '/delivery/shipment-save',
                        'print' => '/delivery/print'
                    ],
                    'payerType' => [									// Допустимые типы плательщиков за доставку;
                        'sender',                                       // sender - магазин может брать деньги с покупателя за доставку и потом расплачивается со службой доставки)
                        'receiver',                                     // receiver - покупатель сам расплачивается напрямую со службой доставки
                    ],
                    'duplicateOrderProductSupported' => true,
                    'codAvailable' => true,
                    'allowTrackNumber' => true,							// boolean		Передавать дополнительно трек номер помимо идентификатора доставки
                    'availableCountries' => [							// Список ISO кодов стран (ISO 3166-1 alpha-2) с которыми работает доставка. Если массив пустой, то нет ограничения на страны
                        'RU'
                    ],
                    'requiredFields' => [
                        'lastName', 									// Фамилия покупателя
                        'phone', 										// Телефон покупателя
                        'email', 										// E-mail покупателя
                        'length', 										// Длина
                        'width', 										// Ширина
                        'height', 										// Высота
                        'deliveryAddress.regionId',
                        'deliveryAddress.cityId',
                        'deliveryTime.from',
                        'deliveryTime.to'
                    ],
                    'statusList' => Setting::getLogsisStatusList(),
                    'deliveryDataFieldList' => $setting->getDeliveryDataFieldList(),
                    'settings' => [
                        'statuses' => $setting->getStatuses(),
                        'deliveryExtraData' => $setting->getDefaultDeliveryExtraData(),
                        'paymentTypes' => $setting->getDefaultPaymentTypes()
                    ],
                ],
            ]
        ];

        if ($response = Yii::$app->retail->moduleEdit($this->getRetailAuthData($setting), $moduleData)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Получение статусов заказов из retailCRM
     * 
     * @param object
     * @return bool
     */

    private function getOrderStatus(Setting $setting): bool
    {
        if ($retailOrderStatuses = Yii::$app->retail->statusesList($this->getRetailAuthData($setting))) {
            foreach ($retailOrderStatuses as $retailOrderStatus) {

                $orderStatus = OrderStatus::find()->where(['setting_id' => $setting->id])->andWhere(['code' => $retailOrderStatus['code']])->one();

                if ($orderStatus && !$retailOrderStatus['active']) {
                    $orderStatus->delete();
                    continue;
                } elseif ($orderStatus && $retailOrderStatus['active']) {
                    $orderStatus->name = $retailOrderStatus['name']; 
                } elseif (!$orderStatus && $retailOrderStatus['active']) {
                    $orderStatus = new OrderStatus([
                        'setting_id' => $setting->id,
                        'name' => $retailOrderStatus['name'],
                        'code' => $retailOrderStatus['code']
                    ]);
                }

                if ($orderStatus && $orderStatus->validate()) $orderStatus->save();
            }
        }
        return true;
    }

    /**
     * Получение данных авторизации для retailCRM
     * 
     * @param object
     * @param array
     */

    private function getRetailAuthData(Setting $setting): array
    {
        return [
            'retailApiUrl' => $setting->retail_api_url,
            'retailApiKey' => $setting->retail_api_key
        ];
    }

    /**
     * Генерация секретного ключа
     * 
     * @return string
     */

    private function generateClientId(): string
    {
        while (true) {
            $clientId = Yii::$app->security->generateRandomString(32);

            if (!Setting::find()->where(['client_id' => $clientId])->one()) {
                return $clientId;
            }
        }
    }

}