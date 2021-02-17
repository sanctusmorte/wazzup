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
 * @property string $channels
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 */
class Setting extends \yii\db\ActiveRecord
{
    const NEEDED_CREDENTIALS = [
        '/api/integration-modules/{code}',
        '/api/integration-modules/{code}/edit',
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
            [['client_id', 'retail_api_url', 'retail_api_key', 'wazzup_api_key'], 'required'],
            [['client_id'], 'string', 'max' => 32],
            [['retail_api_url', 'retail_api_key', 'wazzup_api_key'], 'string', 'max' => 255],
            ['retail_api_url', 'match', 'pattern' => '/^https:\/\/.+\.retailcrm\.+[a-zA-Z]+$/i', 'message' => 'Формат ссылки должен быть https://YOUR-DOMAIN.retailcrm.DOMAIN'],
            ['retail_api_url', 'url', 'validSchemes' => ['https']],
            [['client_id'], 'unique'],
            [['retail_api_url'], 'unique'],
            ['retail_api_url', 'validateApiUrl'],
            ['retail_api_key', 'validateApiKey'],
            ['wazzup_api_key', 'validateWazzupApikey'],
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
     * Валидация ключа Wazzup
     */

    public function validateWazzupApikey()
    {
        if ($this->wazzup_api_key) {
            $response = Yii::$app->wazzup->checkApiKey($this->wazzup_api_key);
            if ($response['success'] === false) {
                $this->addError('wazzup_api_key',  'API ключ Wazzup неверный.');
            }
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

            if ($credentials !== null) {
                if (isset($credentials['credentials'])) {
                    foreach (self::NEEDED_CREDENTIALS as $NEEDED_CREDENTIAL) {
                        if (array_search($NEEDED_CREDENTIAL, $credentials['credentials']) === false) {
                            $this->addError('retail_api_key',  'Недоступен метод '.$NEEDED_CREDENTIAL.'');
                        }
                    }
                } else {
                    $this->addError('retail_api_key',  'Выберите как минимум 1 разрешенный метод в настройках API-ключа.');
                }
            } else {
                $this->addError('retail_api_key',  'Некорректно указана ссылка на retailCRM или ключ доступа к api.');
            }
        }
    }

    public function getExistChannels()
    {
        return [
            'Instagram' => 'Instagram',
            'Whatsupp' => 'Whatsupp',
            'Telegram' => 'Telegram'
        ];
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
            'channels' => 'Каналы',
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

