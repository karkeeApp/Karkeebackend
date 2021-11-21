<?php

use yii\db\Migration;

/**
 * Class m211111_034327_alter_account_user_settings_table
 */
class m211111_034327_alter_account_user_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user_settings` 
            ADD COLUMN `num_days_expiry` INT DEFAULT '30' AFTER `is_one_approval`;
        ");
        
        $this->execute("
            ALTER TABLE `account` 
            ADD COLUMN `num_days_expiry` INT DEFAULT '30' AFTER `is_one_approval`;
        ");

        $this->execute("
            ALTER TABLE `settings` 
            ADD COLUMN `num_days_expiry` INT DEFAULT '30' AFTER `is_one_approval`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211111_034327_alter_account_user_settings_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211111_034327_alter_account_user_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
