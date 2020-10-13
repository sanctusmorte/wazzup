<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%payment_type_setting}}`.
 */
class m201013_115839_create_payment_type_setting_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%payment_type_setting}}', [
            'id' => $this->primaryKey(),
            'setting_id' => $this->integer()->notNull(),
            'payment_type_id' => $this->integer()->notNull(),
            'active' => $this->boolean()->defaultValue(false),
            'cod' => $this->boolean()->defaultValue(false)
        ]);

        // creates index for column `setting_id`
        $this->createIndex(
            '{{%idx-payment_type_setting-setting_id}}',
            '{{%payment_type_setting}}',
            'setting_id'
        );

        // add foreign key for table `{{%setting}}`
        $this->addForeignKey(
            '{{%fk-payment_type_setting-setting_id}}',
            '{{%payment_type_setting}}',
            'setting_id',
            '{{%setting}}',
            'id',
            'CASCADE'
        );

        // creates index for column `payment_type_id`
        $this->createIndex(
            '{{%idx-payment_type_setting-payment_type_id}}',
            '{{%payment_type_setting}}',
            'payment_type_id'
        );

        // add foreign key for table `{{%setting}}`
        $this->addForeignKey(
            '{{%fk-payment_type_setting-payment_type_id}}',
            '{{%payment_type_setting}}',
            'payment_type_id',
            '{{%payment_type}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%payment_type_setting}}');
    }
}
