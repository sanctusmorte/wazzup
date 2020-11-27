<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%column_is_single_cost_for_setting}}`.
 */
class m201127_184208_drop_column_is_single_cost_for_setting_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%setting}}', 'is_single_cost');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%setting}}', 'is_single_cost', $this->boolean()->defaultValue(false));
    }
}
