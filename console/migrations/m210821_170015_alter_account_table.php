<?php

use yii\db\Migration;

/**
 * Class m210821_170015_alter_account_table
 */
class m210821_170015_alter_account_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `account` 
            ADD COLUMN `confirmed_by` int DEFAULT NULL AFTER `status`,
            ADD COLUMN `approved_by` int DEFAULT NULL AFTER `confirmed_by`,
            ADD COLUMN `approved_at` DATETIME DEFAULT NULL AFTER `approved_by`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210821_170015_alter_account_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210821_170015_alter_account_table cannot be reverted.\n";

        return false;
    }
    */
}
