<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "setting_shop".
 *
 * @property int $setting_id
 * @property int $shop_id
 *
 * @property Setting $setting
 * @property Shop $shop
 */
class SettingShop extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%setting_shop}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['setting_id', 'shop_id'], 'required'],
            [['setting_id', 'shop_id'], 'integer'],
            [['setting_id', 'shop_id'], 'unique', 'targetAttribute' => ['setting_id', 'shop_id']],
            [['setting_id'], 'exist', 'skipOnError' => true, 'targetClass' => Setting::className(), 'targetAttribute' => ['setting_id' => 'id']],
            [['shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shop::className(), 'targetAttribute' => ['shop_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'setting_id' => 'Setting ID',
            'shop_id' => 'Shop ID',
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

    /**
     * Gets query for [[Shop]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getShop()
    {
        return $this->hasOne(Shop::className(), ['id' => 'shop_id']);
    }
}
