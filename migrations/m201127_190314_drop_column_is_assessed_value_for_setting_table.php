<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%column_is_assessed_value_for_setting}}`.
 */
class m201127_190314_drop_column_is_assessed_value_for_setting_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropColumn('{{%setting}}', 'is_assessed_value');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->addColumn('{{%setting}}', 'is_assessed_value', $this->boolean()->defaultValue(false));
    }
}
