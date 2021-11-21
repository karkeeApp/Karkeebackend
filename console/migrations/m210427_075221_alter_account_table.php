<?php

use yii\db\Migration;

/**
 * Class m210427_075221_alter_account_table
 */
class m210427_075221_alter_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `account` 
            ADD COLUMN `logo` TEXT DEFAULT NULL AFTER `email`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210427_075221_alter_account_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210427_075221_alter_account_table cannot be reverted.\n";

        return false;
    }
    */
}
