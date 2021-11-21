<?php

use yii\db\Migration;

/**
 * Class m210907_153517_alter_account_settings_table
 */
class m210907_153517_alter_account_settings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `settings` 
            ADD COLUMN `days_unverified_reg` int DEFAULT '7' AFTER `status`;
        ");

        $this->execute("
            ALTER TABLE `account` 
            ADD COLUMN `days_unverified_reg` int DEFAULT '7' AFTER `status`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210907_153517_alter_account_settings_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210907_153517_alter_account_settings_table cannot be reverted.\n";

        return false;
    }
    */
}
