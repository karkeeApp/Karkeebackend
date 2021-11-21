<?php

use yii\db\Migration;

/**
 * Class m210915_045539_alter_account_and_settings_table
 */
class m210915_045539_alter_account_and_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `account` 
            ADD COLUMN `enable_banner` TINYINT DEFAULT '1' AFTER `enable_ads`;
        ");
        $this->execute("
            ALTER TABLE `settings` 
            ADD COLUMN `enable_banner` TINYINT DEFAULT '1' AFTER `enable_ads`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210915_045539_alter_account_and_settings_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210915_045539_alter_account_and_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
