<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%setting_shop}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%setting}}`
 * - `{{%shop}}`
 */
class m200901_080740_create_junction_table_setting_and_shop_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%setting_shop}}', [
            'setting_id' => $this->integer(),
            'shop_id' => $this->integer(),
            'PRIMARY KEY(setting_id, shop_id)',
        ]);

        // creates index for column `setting_id`
        $this->createIndex(
            '{{%idx-setting_shop-setting_id}}',
            '{{%setting_shop}}',
            'setting_id'
        );

        // add foreign key for table `{{%setting}}`
        $this->addForeignKey(
            '{{%fk-setting_shop-setting_id}}',
            '{{%setting_shop}}',
            'setting_id',
            '{{%setting}}',
            'id',
            'CASCADE'
        );

        // creates index for column `shop_id`
        $this->createIndex(
            '{{%idx-setting_shop-shop_id}}',
            '{{%setting_shop}}',
            'shop_id'
        );

        // add foreign key for table `{{%shop}}`
        $this->addForeignKey(
            '{{%fk-setting_shop-shop_id}}',
            '{{%setting_shop}}',
            'shop_id',
            '{{%shop}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%setting}}`
        $this->dropForeignKey(
            '{{%fk-setting_shop-setting_id}}',
            '{{%setting_shop}}'
        );

        // drops index for column `setting_id`
        $this->dropIndex(
            '{{%idx-setting_shop-setting_id}}',
            '{{%setting_shop}}'
        );

        // drops foreign key for table `{{%shop}}`
        $this->dropForeignKey(
            '{{%fk-setting_shop-shop_id}}',
            '{{%setting_shop}}'
        );

        // drops index for column `shop_id`
        $this->dropIndex(
            '{{%idx-setting_shop-shop_id}}',
            '{{%setting_shop}}'
        );

        $this->dropTable('{{%setting_shop}}');
    }
}
