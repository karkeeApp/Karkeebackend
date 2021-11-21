<?php

use yii\db\Migration;

/**
 * Class m210820_112508_alter_account_table
 */
class m210820_112508_alter_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `account` 
            ADD COLUMN `is_one_approval` tinyint DEFAULT '0' AFTER `status`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210820_112508_alter_account_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210820_112508_alter_account_table cannot be reverted.\n";

        return false;
    }
    */
}
