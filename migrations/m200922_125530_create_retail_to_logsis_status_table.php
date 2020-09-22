<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%retail_to_logsis_status}}`.
 */
class m200922_125530_create_retail_to_logsis_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%retail_to_logsis_status}}', [
            'id' => $this->primaryKey(),
            'setting_id' => $this->integer()->notNull(),
            'order_status_id' => $this->integer()->notNull(),
            'logsis_status_id' => $this->integer()
        ]);

        // creates index for column `setting_id`
        $this->createIndex(
            '{{%idx-retail_to_logsis_status-setting_id}}',
            '{{%retail_to_logsis_status}}',
            'setting_id'
        );

        // add foreign key for table `{{%setting}}`
        $this->addForeignKey(
            '{{%fk-retail_to_logsis_status-setting_id}}',
            '{{%retail_to_logsis_status}}',
            'setting_id',
            '{{%setting}}',
            'id',
            'CASCADE'
        );

        // creates index for column `order_status_id`
        $this->createIndex(
            '{{%idx-retail_to_logsis_status-order_status_id}}',
            '{{%retail_to_logsis_status}}',
            'order_status_id'
        );

        // add foreign key for table `{{%setting}}`
        $this->addForeignKey(
            '{{%fk-retail_to_logsis_status-order_status_id}}',
            '{{%retail_to_logsis_status}}',
            'order_status_id',
            '{{%order_status}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%retail_to_logsis_status}}');
    }
}
