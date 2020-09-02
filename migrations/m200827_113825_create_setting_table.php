<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%setting}}`.
 */
class m200827_113825_create_setting_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%setting}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->string(32)->unique()->notNull(),
            'retail_api_url' => $this->string(255)->unique()->notNull(),
            'retail_api_key' => $this->string(255)->notNull(),
            'apikey' => $this->string(255)->notNull(),
            'cost_delivery' => $this->double(),
            'markup' => $this->double(), 
            'tax_product' => $this->string(),
            'tax_delivery' => $this->string(),
            'prefix_shop' => $this->string(),
            'is_active' => $this->boolean()->defaultValue(false),
            'is_freeze' => $this->boolean()->defaultValue(false),
            'is_first_active' => $this->boolean()->defaultValue(true),
            'is_payment_type' => $this->boolean()->defaultValue(false),
            'is_single_cost' => $this->boolean()->defaultValue(false),
            'is_assessed_value' => $this->boolean()->defaultValue(false),
            'is_partial_redemption' => $this->boolean()->defaultValue(false),
            'is_fitting' => $this->boolean()->defaultValue(false),
            'is_sms' => $this->boolean()->defaultValue(false),
            'is_open' => $this->boolean()->defaultValue(false),
            'is_additional_call' => $this->boolean()->defaultValue(false),
            'is_return_doc' => $this->boolean()->defaultValue(false),
            'is_skid' => $this->boolean()->defaultValue(false),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%setting}}');
    }
}
