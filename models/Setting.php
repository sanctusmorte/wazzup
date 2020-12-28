<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "setting".
 *
 * @property int $id
 * @property string $client_id
 * @property string $retail_api_url
 * @property string $retail_api_key
 * @property string $apikey
 * @property float|null $cost_delivery
 * @property float|null $markup
 * @property int|null $is_payment_type
 * @property string|null $tax_product
 * @property string|null $tax_delivery
 * @property string|null $prefix_shop
 * @property int|null $is_active
 * @property int|null $is_freeze
 * @property int|null $is_first_active
 * @property int|null $is_partial_redemption
 * @property int|null $is_fitting
 * @property int|null $is_sms
 * @property int|null $is_open
 * @property int|null $is_additional_call
 * @property int|null $is_return_doc
 * @property int|null $is_skid
 * @property int|null $is_cargo_lift
 * @property int|null $is_partial_return
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property OrderStatus[] $orderStatuses
 * @property Shop[] $shops
 */
class Setting extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 1;
    const STATUS_DISABLE = 0;

    const STATUS_FREEZE = 1;
    const STATUS_UNFREEZE = 0;

    const STATUS_FIRST_ACTIVE = 1;
    const STATUS_FIRST_DISABLE = 0;

    public $shop_ids;
    public $order_statuses;
    public $payment_types;
    public $payment_types_cod;

    const ORDER_STATUS_LOGSIS = [
        1 => 'Новый заказ',
        2 => 'Подтвержден',
        3 => 'На складе',
        4 => 'На доставке', 
        5 => 'Доставлен',
        6 => 'Частичный отказ',
        7 => 'Полный отказ',
        8 => 'Отмена',
        9 => 'На пути в город доставки'
     ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%setting}}';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['client_id', 'retail_api_url', 'retail_api_key', 'apikey'], 'required'],
            [['cost_delivery'], 'number'],
            [['is_active', 'is_freeze', 'is_first_active', 'is_payment_type', 'is_partial_redemption', 'is_fitting', 'is_sms', 'is_open', 'is_additional_call', 'is_return_doc', 'is_skid', 'is_nds', 'is_cargo_lift', 'is_partial_return', 'is_packaging', 'created_at', 'updated_at'], 'integer'],
            [['client_id'], 'string', 'max' => 32],
            [['retail_api_url', 'retail_api_key', 'apikey', 'prefix_shop', 'tax_product', 'tax_delivery'], 'string', 'max' => 255],
            ['retail_api_url', 'match', 'pattern' => '/^https:\/\/.+\.retailcrm\.+[a-zA-Z]+$/i', 'message' => 'Формат ссылки должен быть https://YOUR-DOMAIN.retailcrm.DOMAIN'],
            ['retail_api_url', 'url', 'validSchemes' => ['https']],
            [['markup'], 'number', 'min' => 1, 'max' => 100],
            [['prefix_shop'], 'string', 'min' => 2, 'max' => 5],
            ['prefix_shop', 'match', 'pattern' => '/^[a-zA-Z]+$/i', 'message' => 'Префикс содержит некорректные символы.'],
            [['client_id'], 'unique'],
            [['retail_api_url'], 'unique'],
            ['retail_api_url', 'validateApiUrl'],
            ['retail_api_key', 'validateApiKey'],
            ['apikey', 'validateApiLogsis'],
            [['shop_ids', 'order_statuses', 'payment_types', 'payment_types_cod'], 'safe']
        ];
    }

    /**
     * Валидация аккаунта retailCRM
     */

    public function validateApiUrl()
    {
        if (substr($this->retail_api_url, -1) == '/') {
            $this->retail_api_url = mb_substr($this->retail_api_url, 0, -1);
        }

        if (self::find()->where(['retail_api_url' => $this->retail_api_url])->andWhere(['!=', 'client_id', $this->client_id])->one()) {
            $this->addError('retail_api_url',  'Данный аккаунт уже зарегистрирован в системе.');
        }
    }

    /**
     * Валидация ключа доступа
     */

    public function validateApiKey()
    {
        if ($this->retail_api_url && $this->retail_api_key) {

            $credentials = Yii::$app->retail->credentials([
                'retailApiUrl' => $this->retail_api_url,
                'retailApiKey' => $this->retail_api_key
            ]);

            if ($credentials) {
                if (isset($credentials['credentials']) && $credentials['credentials']) {

                    if (!$this->searchArray($credentials['credentials'], '/api/integration-modules/{code}')) {
                        $this->addError('retail_api_key',  'Недоступен метод /api/integration-modules/{code}');
                    }
                    if (!$this->searchArray($credentials['credentials'], '/api/integration-modules/{code}/edit')) {
                        $this->addError('retail_api_key',  'Недоступен метод /api/integration-modules/{code}/edit');
                    }
                    if (!$this->searchArray($credentials['credentials'], '/api/reference/sites')) {
                        $this->addError('retail_api_key',  'Недоступен метод /api/reference/sites');
                    }
                    if (!$this->searchArray($credentials['credentials'], '/api/reference/payment-types')) {
                        $this->addError('retail_api_key',  'Недоступен метод /api/reference/payment-types');
                    }
                    if (!$this->searchArray($credentials['credentials'], '/api/orders/statuses')) {
                        $this->addError('retail_api_key',  'Недоступен метод /api/orders/statuses');
                    }
                    if (!$this->searchArray($credentials['credentials'], '/api/reference/statuses')) {
                        $this->addError('retail_api_key',  'Недоступен метод /api/reference/statuses');
                    }
                    if (!$this->searchArray($credentials['credentials'], '/api/orders')) {
                        $this->addError('retail_api_key',  'Недоступен метод /api/orders');
                    }
                    if (!$this->searchArray($credentials['credentials'], '/api/orders/{externalId}/edit')) {
                        $this->addError('retail_api_key',  'Недоступен метод /api/orders/{externalId}/edit');
                    }
                    if (!$this->searchArray($credentials['credentials'], '/api/orders/{externalId}')) {
                        $this->addError('retail_api_key',  'Недоступен метод /api/orders/{externalId}');
                    }
                } else {
                    $this->addError('retail_api_key',  'Недоступен метод /api/reference/sites.');
                }
            } else {
                $this->addError('retail_api_key',  'Некорректно указана ссылка на retailCRM или ключ доступа к api.');
            }
        }
    }

    /**
     * Валидация токена Logsis
     */

    public function validateApiLogsis()
    {
        $response = Yii::$app->logsis->testApiKey($this->apikey);

        if ($response['status'] !== '200') {
            $this->addError('apikey',  'Некорректно указан токен для Logsis.'); 
        }
    }

    /**
     * Поиск элемента в массиве
     * 
     * @param array
     * @param string
     * @return boolean
     */

    private function searchArray(array $credentials, string $param): bool
    {
        foreach ($credentials as $credential) {
            if ($credential == $param) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'client_id' => 'Client ID',
            'retail_api_url' => 'Ссылка на retailCRM вида: https://YOUR-DOMAIN.retailcrm.ru',
            'retail_api_key' => 'API-ключ',
            'apikey' => 'API-ключ',
            'cost_delivery' => 'Фиксированная стоимость стоимости доставки',
            'markup' => 'Наценка % (от стоимости доставки)',
            'prefix_shop' => 'Префикс магазина',
            'is_payment_type' => 'Прием денежных средств по умолчанию',
            'tax_product' => 'Вид налога на товар',
            'tax_delivery' => 'Вид налога на доставку',
            'shop_ids' => 'Магазины',
            'is_active' => 'Is Active',
            'is_freeze' => 'Is Freeze',
            'is_first_active' => 'Is First Active',
            'is_partial_redemption' => 'Частичный выкуп',
            'is_fitting' => 'Примерка товаров',
            'is_sms' => 'SMS информирование',
            'is_open' => 'Возможность вскрытия заказа',
            'is_additional_call' => 'Дополнительный звонок клиенту',
            'is_return_doc' => 'Возврат накладных / документов, вложенных в заказ',
            'is_skid' => 'Занос/подъем КГТ до квартиры',
            'is_nds' => 'Начислять НДС',
            'is_cargo_lift' => 'Грузовой лифт',
            'is_partial_return' => 'Частичный возврат',
            'is_packaging' => 'Упаковка заказа',
            'order_statuses' => 'Статус в retailCRM',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[OrderStatuses]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderStatuses()
    {
        return $this->hasMany(OrderStatus::className(), ['setting_id' => 'id']);
    }

    /**
     * Gets query for [[Shops]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShops()
    {
        return $this->hasMany(Shop::className(), ['setting_id' => 'id']);
    }

    public function getArrayShops()
    {
        return ArrayHelper::map($this->hasMany(Shop::className(), ['setting_id' => 'id'])->asArray()->all(), 'id', 'name');
    }

    public function getSettingShop()
    {
        return $this->hasOne(Shop::className(), ['id' => 'shop_id'])->viaTable('{{%setting_shop}}', ['setting_id' => 'id']);
    }

    public function getSettingShops()
    {
        return $this->hasMany(Shop::className(), ['id' => 'shop_id'])->viaTable('{{%setting_shop}}', ['setting_id' => 'id']);
    }

    public function getRetailToLogsisStatus()
    {
        return $this->hasMany(RetailToLogsisStatus::className(), ['setting_id' => 'id']);
    }

    public function getRetailToLogsisStatusByLogsisStatusId(int $logsis_status_id)
    {
        return $this->hasOne(RetailToLogsisStatus::className(), ['setting_id' => 'id'])->where(['logsis_status_id' => $logsis_status_id])->one();
    }

    public function getPaymentTypes()
    {
        return $this->hasMany(PaymentType::className(), ['setting_id' => 'id']);
    }

    public function getPaymentType()
    {
        return $this->hasOne(PaymentType::className(), ['setting_id' => 'id']);
    }

    public function getPaymentTypesSetting()
    {
        return $this->hasMany(PaymentTypeSetting::className(), ['setting_id' => 'id']);
    }

    public function getPaymentTypeSetting()
    {
        return $this->hasOne(PaymentTypeSetting::className(), ['setting_id' => 'id']);
    }

    public function getArrayOrderStatuses()
    {
        return ArrayHelper::map($this->hasMany(OrderStatus::className(), ['setting_id' => 'id'])->asArray()->all(), 'id', 'name');
    }

    public function getShopValues()
    {
        if ($shops = $this->settingShops) {

            return ArrayHelper::getColumn($shops, 'id');
        }

        return [];
    }

    /**
     * Формирование статусов 
     * 
     * @return array
     */

    public static function getLogsisStatusList(): array 
    {
        $statusList = [];

        foreach (self::ORDER_STATUS_LOGSIS as $key => $status) {
            $statusList[] = [
                'code' => $key,
                'name' => $status,
                'isEditable' => true,
            ];
        }

        return $statusList;
    }

    /**
     * Формирование массива с данными
     * 
     * @return array
     */

    public function getDeliveryDataFieldList(): array
    {
        return [
            [
                'code' => 'is_partial_redemption',
                'label' => $this->getAttributeLabel('is_partial_redemption'),
                // 'hint' => $this->getAttributeLabel('is_partial_redemption'),
                'type' => 'checkbox',
                'required' => false,
                'affectsCost' => true,
                'editable' => true,
            ],
            [
                'code' => 'is_fitting',
                'label' => $this->getAttributeLabel('is_fitting'),
                // 'hint' => $this->getAttributeLabel('is_fitting'),
                'type' => 'checkbox',
                'required' => false,
                'affectsCost' => true,
                'editable' => true,
            ],
            [
                'code' => 'is_sms',
                'label' => $this->getAttributeLabel('is_sms'),
                // 'hint' => $this->getAttributeLabel('is_sms'),
                'type' => 'checkbox',
                'required' => false,
                'affectsCost' => true,
                'editable' => true,
            ],
            [
                'code' => 'is_open',
                'label' => $this->getAttributeLabel('is_open'),
                // 'hint' => $this->getAttributeLabel('is_open'),
                'type' => 'checkbox',
                'required' => false,
                'affectsCost' => true,
                'editable' => true,
            ],
            [
                'code' => 'is_additional_call',
                'label' => $this->getAttributeLabel('is_additional_call'),
                // 'hint' => $this->getAttributeLabel('is_additional_call'),
                'type' => 'checkbox',
                'required' => false,
                'affectsCost' => true,
                'editable' => true,
            ],
            [
                'code' => 'is_return_doc',
                'label' => $this->getAttributeLabel('is_return_doc'),
                // 'hint' => $this->getAttributeLabel('is_return_doc'),
                'type' => 'checkbox',
                'required' => false,
                'affectsCost' => true,
                'editable' => true,
            ],
            [
                'code' => 'is_skid',
                'label' => $this->getAttributeLabel('is_skid'),
                // 'hint' => $this->getAttributeLabel('is_skid'),
                'type' => 'checkbox',
                'required' => false,
                'affectsCost' => true,
                'editable' => true,
            ],
            [
                'code' => 'is_payment_type',
                'label' => $this->getAttributeLabel('is_payment_type'),
                // 'hint' => $this->getAttributeLabel('is_payment_type'),
                'type' => 'checkbox',
                'required' => false,
                'affectsCost' => true,
                'editable' => true,
            ],
            [
                'code' => 'is_nds',
                'label' => $this->getAttributeLabel('is_nds'),
                // 'hint' => $this->getAttributeLabel('is_nds'),
                'type' => 'checkbox',
                'required' => false,
                'affectsCost' => true,
                'editable' => true,
            ],
            [
                'code' => 'is_cargo_lift',
                'label' => $this->getAttributeLabel('is_cargo_lift'),
                // 'hint' => $this->getAttributeLabel('is_cargo_lift'),
                'type' => 'checkbox',
                'required' => false,
                'affectsCost' => true,
                'editable' => true,
            ],
            [
                'code' => 'is_partial_return',
                'label' => $this->getAttributeLabel('is_partial_return'),
                // 'hint' => $this->getAttributeLabel('is_partial_return'),
                'type' => 'checkbox',
                'required' => false,
                'affectsCost' => true,
                'editable' => true,
            ],
            [
                'code' => 'is_packaging',
                'label' => $this->getAttributeLabel('is_packaging'),
                // 'hint' => $this->getAttributeLabel('is_packaging'),
                'type' => 'checkbox',
                'required' => false,
                'affectsCost' => true,
                'editable' => true,
            ]
        ];
    }

    /**
     * Формирование массива с сопоставленными статусами
     * 
     * @return array
     */

    public function getStatuses(): array
    {
        $statuses = [];

        foreach ($this->retailToLogsisStatus as $status) {
            $statuses[] = [
                'code' => $status->orderStatus->code,
                'trackingStatusCode' => $status->logsis_status_id
            ];
        }

        return $statuses;
    }

    /**
     * Формирование массива с типами оплат
     * 
     * @return array
     */

    public function getDefaultPaymentTypes(): array
    {
        $paymentTypes = [];

        foreach ($this->paymentTypesSetting as $paymentTypeSetting) {
            $paymentTypes[] = [
                'code' => $paymentTypeSetting->paymentType->code,
                'active' => ($paymentTypeSetting->active) ? true : false,
                'cod' => ($paymentTypeSetting->cod) ? true : false
            ];
        }

        return $paymentTypes;
    }

    /**
     * Формирование массива выбранных полей для 
     * 
     * @return array
     */

    public function getDefaultDeliveryExtraData(): array 
    {
        return [
            'is_payment_type' => ($this->is_payment_type) ? true : false,
            'is_partial_redemption' => ($this->is_partial_redemption) ? true : false,
            'is_fitting' => ($this->is_fitting) ? true : false,
            'is_sms' => ($this->is_sms) ? true : false,
            'is_open' => ($this->is_open) ? true : false,
            'is_additional_call' => ($this->is_additional_call) ? true : false,
            'is_return_doc' => ($this->is_return_doc) ? true : false,
            'is_skid' => ($this->is_skid) ? true : false,
            'is_nds' => ($this->is_nds) ? true : false,
            'is_cargo_lift' => ($this->is_cargo_lift) ? true : false,
            'is_partial_return' => ($this->is_partial_return) ? true : false,
            'is_packaging' => ($this->is_packaging) ? true : false
        ];
    }

    /**
     * Получение данных авторизации
     * 
     * @return array
     */

    public function getRetailAuthData(): array
    {
        return [
            'retailApiUrl' => $this->retail_api_url,
            'retailApiKey' => $this->retail_api_key
        ];
    }
    
}

