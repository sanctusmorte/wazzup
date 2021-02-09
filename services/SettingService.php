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
     * @return array|mixed
     */
    public function getSettingId()
    {
        $clientId = Yii::$app->request->post('clientId');
        if ($clientId === null) {
            $clientId = Yii::$app->session->get('clientId');
        }
        return $clientId;
    }

    /**
     * @param $settingId
     */
    public function setSettingIdInSession($settingId)
    {
        Yii::$app->session->set('clientId', $settingId);
    }

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
     * @param int $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getSettingById($id)
    {
        if ($setting = Setting::find()->where(['id' => $id])->one()) {
            return $setting;
        } else {
            return null;
        }
    }

    /**
     * Сохранение настроек
     * 
     * @param object $setting
     * @return boolean
     */

    public function save(Setting $setting): bool
    {
        $setting->save();
       // $this->moduleEdit($setting);
        Yii::$app->session->set('clientId', $setting->client_id);
        Yii::$app->getSession()->setFlash("success", "Настройки модуля сохранены.");
        return true;

//        $transaction = Yii::$app->db->beginTransaction();
//        try {
//            if ($setting->isNewRecord) {
//                $setting->is_active = $setting::STATUS_ACTIVE;
//                $setting->is_freeze = $setting::STATUS_UNFREEZE;
//            }
//
//            $setting->save();
//
//            if ($settingShops = $setting->settingShops) {
//                foreach ($settingShops as $settingShop) {
//                    $setting->unlink('settingShop', $settingShop, true);
//                }
//            }
//
//            if ($setting->shop_ids) {
//                foreach ($setting->shop_ids as $shop_id) {
//                    $settingShop = new SettingShop([
//                        'setting_id' => $setting->id,
//                        'shop_id' => $shop_id
//                    ]);
//
//                    if ($settingShop->validate()) $settingShop->save();
//                }
//            }
//
//            if ($orderStatuses = $setting->order_statuses) $this->synchStatusSave($setting, $orderStatuses);
//            if ($setting->payment_types && $setting->payment_types_cod) $this->synchPaymentTypeSave($setting);
//
//            $this->getShops($setting);
//            $this->getOrderStatus($setting);
//            $this->getPaymentType($setting);
//            $this->moduleEdit($setting);
//
//            $transaction->commit();
//
//            Yii::$app->session->set('clientId', $setting->client_id);
//            Yii::$app->getSession()->setFlash("success", "Настройки модуля сохранены.");
//
//            return true;
//
//        } catch(\Exception $th) {
//            $transaction->rollBack();
//
//            Yii::error($th->getMessage(), 'Ошибка сохранения настроек модуля.');
//            Yii::$app->getSession()->setFlash("error", "Ошибка сохранения настроек модуля.");
//
//            return false;
//        }
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
     * Обновление настроек модуля
     * 
     * @param object
     * @return boolean
     */

    private function moduleEdit(Setting $setting): bool
    {
        $moduleData = [
            'integrationCode' => 'wazzup',
            'code' => 'wazzup',
            'clientId' => $setting->client_id,
            'baseUrl' => 'https://wazzup.imb-service.ru',
            'accountUrl' => 'https://wazzup.imb-service.ru/setting',
            'active' => true,
            'freeze' => false,
            'name' => 'Wazzup чаты v1.0 [dev-max]',
            'actions' => [
                'activity' => '/setting/activity'
            ],
        ];

        if ($response = Yii::$app->retail->moduleEdit($this->getRetailAuthData($setting), $moduleData)) {
            return true;
        } else {
            return false;
        }
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