<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "retail_to_logsis_status".
 *
 * @property int $id
 * @property int $setting_id
 * @property int $order_status_id
 * @property int|null $logsis_status_id
 *
 * @property OrderStatus $orderStatus
 * @property Setting $setting
 */
class RetailToLogsisStatus extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%retail_to_logsis_status}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['setting_id', 'order_status_id'], 'required'],
            [['setting_id', 'order_status_id', 'logsis_status_id'], 'integer'],
            [['order_status_id'], 'exist', 'skipOnError' => true, 'targetClass' => OrderStatus::className(), 'targetAttribute' => ['order_status_id' => 'id']],
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
            'order_status_id' => 'Order Status ID',
            'logsis_status_id' => 'Logsis Status ID',
        ];
    }

    /**
     * Gets query for [[OrderStatus]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrderStatus()
    {
        return $this->hasOne(OrderStatus::className(), ['id' => 'order_status_id']);
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
