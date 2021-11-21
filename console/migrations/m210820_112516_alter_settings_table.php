<?php

use yii\db\Migration;

/**
 * Class m210820_112516_alter_settings_table
 */
class m210820_112516_alter_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `settings` 
            ADD COLUMN `is_one_approval` tinyint DEFAULT '1' AFTER `status`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210820_112516_alter_settings_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210820_112516_alter_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
