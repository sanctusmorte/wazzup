<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "payment_type_setting".
 *
 * @property int $id
 * @property int $setting_id
 * @property int $payment_type_id
 * @property int|null $cod
 *
 * @property PaymentType $paymentType
 * @property Setting $setting
 */
class PaymentTypeSetting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%payment_type_setting}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['setting_id', 'payment_type_id'], 'required'],
            [['setting_id', 'payment_type_id', 'active', 'cod'], 'integer'],
            [['payment_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PaymentType::className(), 'targetAttribute' => ['payment_type_id' => 'id']],
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
            'payment_type_id' => 'Payment Type ID',
            'active' => 'Active',
            'cod' => 'Cod',
        ];
    }

    /**
     * Gets query for [[PaymentType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPaymentType()
    {
        return $this->hasOne(PaymentType::className(), ['id' => 'payment_type_id']);
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
