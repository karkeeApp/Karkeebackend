<?php

use yii\db\Migration;

/**
 * Class m211110_115211_alter_account_user_settings_table
 */
class m211110_115211_alter_account_user_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user_settings` 
            ADD COLUMN `renewal_fee` Decimal(10,3) DEFAULT '0.0' AFTER `is_one_approval`;
        ");
        
        $this->execute("
            ALTER TABLE `account` 
            ADD COLUMN `renewal_fee` Decimal(10,3) DEFAULT '0.0' AFTER `is_one_approval`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211110_115211_alter_account_user_settings_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211110_115211_alter_account_user_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
