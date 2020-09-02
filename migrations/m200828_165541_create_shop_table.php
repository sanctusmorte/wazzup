<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shop}}`.
 */
class m200828_165541_create_shop_table extends Migration
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

        $this->createTable('{{%shop}}', [
            'id' => $this->primaryKey(),
            'setting_id' => $this->integer()->notNull(),
            'name' => $this->string(255)->notNull(),
            'code' => $this->string(255)->notNull(),
            'url' => $this->string(255)->notNull()
        ], $tableOptions);

        // creates index for column `setting_id`
        $this->createIndex(
            '{{%idx-shop-setting_id}}',
            '{{%shop}}',
            'setting_id'
        );

        // add foreign key for table `{{%setting}}`
        $this->addForeignKey(
            '{{%fk-shop-setting_id}}',
            '{{%shop}}',
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
        $this->dropTable('{{%shop}}');
    }
}
