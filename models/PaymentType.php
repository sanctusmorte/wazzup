<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment_type".
 *
 * @property int $id
 * @property int $setting_id
 * @property string $name
 * @property string $code
 *
 * @property Setting $setting
 */
class PaymentType extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%payment_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['setting_id', 'name', 'code'], 'required'],
            [['setting_id'], 'integer'],
            [['name', 'code'], 'string', 'max' => 128],
            [['setting_id'], 'exist', 'skipOnError' => true, 'targetClass' => Setting::className(), 'targetAttribute' => ['setting_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'setting_id' => 'Setting ID',
            'name' => 'Name',
            'code' => 'Code',
        ];
    }

    /**
     * Gets query for [[Setting]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSetting()
    {
        return $this->hasOne(Setting::className(), ['id' => 'setting_id']);
    }
}
