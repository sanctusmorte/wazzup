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
            'wazzup_api_key' => $this->string(255)->notNull(),
            'mg_transport_token' => $this->string(255),
            'wazzup_channels' => $this->text(),
            'mg_transport_endpoint_url' => $this->string(255),
            'wazzup_instagram_imb_service_ex_id' => $this->string(255),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),

            'is_active' => $this->boolean(),
            'is_freeze' => $this->boolean(),
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

