<?php

use yii\db\Migration;

/**
 * Class m211110_111433_alter_user_payment_table
 */
class m211110_111433_alter_user_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user_payment` 
            ADD COLUMN `renewal_id` INT DEFAULT NULL AFTER `account_id`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211110_111433_alter_user_payment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211110_111433_alter_user_payment_table cannot be reverted.\n";

        return false;
    }
    */
}
