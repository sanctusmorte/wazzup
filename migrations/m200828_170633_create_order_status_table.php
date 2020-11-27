<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%order_status}}`.
 */
class m200828_170633_create_order_status_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%order_status}}', [
            'id' => $this->primaryKey(),
            'setting_id' => $this->integer()->notNull(),
            'name' => $this->string(128)->notNull(),
            'code' => $this->string(128)->notNull(),
        ], $tableOptions);

        // creates index for column `setting_id`
        $this->createIndex(
            '{{%idx-order_status-setting_id}}',
            '{{%order_status}}',
            'setting_id'
        );

        // add foreign key for table `{{%setting}}`
        $this->addForeignKey(
            '{{%fk-order_status-setting_id}}',
            '{{%order_status}}',
            'setting_id',
            '{{%setting}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order_status}}');
    }
}
