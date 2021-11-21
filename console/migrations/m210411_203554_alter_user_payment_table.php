<?php

use yii\db\Migration;

/**
 * Class m210411_203554_alter_user_payment_table
 */
class m210411_203554_alter_user_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user_payment_attachment` 
            ADD COLUMN `amount` DECIMAL(10,3) DEFAULT '0' AFTER `status`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m210411_203554_alter_user_payment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m210411_203554_alter_user_payment_table cannot be reverted.\n";

        return false;
    }
    */
}
