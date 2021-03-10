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
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%setting}}', [
            'id' => $this->primaryKey(),
            'client_id' => $this->string(32)->unique()->notNull(),
            'retail_api_url' => $this->string(255)->unique()->notNull(),
            'retail_api_key' => $this->string(255)->notNull(),
            'wazzup_api_key' => $this->string(255)->notNull(),
            'mg_transport_token' => $this->string(255),
            'wazzup_channels' => $this->text(),
            'mg_transport_endpoint_url' => $this->string(255),

            'retail_crm_web_hook_uuid' => $this->string(255),
            'wazzup_web_hook_uuid' => $this->string(255),

            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),

            'is_active' => $this->boolean(),
            'is_freeze' => $this->boolean(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%setting}}');
    }
}
