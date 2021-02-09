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
 * @property string $wazzup_api_key
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 */
class Setting extends \yii\db\ActiveRecord
{
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
            [['client_id', 'retail_api_url', 'retail_api_key', 'wazzup_api_key'], 'required'],
            [['client_id'], 'string', 'max' => 32],
            [['retail_api_url', 'retail_api_key', 'wazzup_api_key'], 'string', 'max' => 255],
            ['retail_api_url', 'match', 'pattern' => '/^https:\/\/.+\.retailcrm\.+[a-zA-Z]+$/i', 'message' => 'Формат ссылки должен быть https://YOUR-DOMAIN.retailcrm.DOMAIN'],
            ['retail_api_url', 'url', 'validSchemes' => ['https']],
            [['client_id'], 'unique'],
            [['retail_api_url'], 'unique'],
            ['retail_api_url', 'validateApiUrl'],
            //['retail_api_key', 'validateApiKey'],
            //['apikey', 'validateApiLogsis'],
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
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'client_id' => 'Client ID',
            'retail_api_url' => 'Ссылка на retailCRM вида: https://YOUR-DOMAIN.retailcrm.ru',
            'retail_api_key' => 'API-ключ',
            'wazzup_api_key' => 'API-ключ Wazzup',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
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

