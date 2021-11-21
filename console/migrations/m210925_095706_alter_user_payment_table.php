<?php

use yii\db\Migration;

/**
 * Class m210925_095706_alter_user_payment_table
 */
class m210925_095706_alter_user_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user_payment` 
            ADD COLUMN `payment_for` TINYINT DEFAULT '0' AFTER `amount`,
            ADD COLUMN `confirmed_by` int DEFAULT NULL AFTER `status`,
            ADD COLUMN `confirmed_at` DATETIME DEFAULT NULL AFTER `confirmed_by`,
            ADD COLUMN `approved_by` int DEFAULT NULL AFTER `confirmed_at`,
            ADD COLUMN `approved_at` DATETIME DEFAULT NULL AFTER `approved_by`;
        ");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210925_095706_alter_user_payment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210925_095706_alter_user_payment_table cannot be reverted.\n";

        return false;
    }
    */
}
