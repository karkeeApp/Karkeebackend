<?php

use yii\db\Migration;

/**
 * Class m200403_075928_account_user
 */
class m200403_075928_account_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {  
        $this->execute("
            ALTER TABLE `account` 
            ADD COLUMN `user_id` BIGINT NULL DEFAULT NULL AFTER `prefix`;
        ");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200403_075928_account_user cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200403_075928_account_user cannot be reverted.\n";

        return false;
    }
    */
}
