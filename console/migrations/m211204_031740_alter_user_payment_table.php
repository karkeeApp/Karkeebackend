<?php

use yii\db\Migration;

/**
 * Class m211204_031740_alter_user_payment_table
 */
class m211204_031740_alter_user_payment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->execute("
            ALTER TABLE `user_payment` 
            ADD COLUMN `log_card` VARCHAR(255) DEFAULT NULL AFTER `filename`;
        ");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211204_031740_alter_user_payment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211204_031740_alter_user_payment_table cannot be reverted.\n";

        return false;
    }
    */
}
